<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\ServiceRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Point d'entrée unique pour /dashboard
     * Redirige selon le rôle de l'utilisateur connecté
     */
    public function index()
    {
        $user = Auth::user();

        // Dashboard pour secrétaire
        if ($user->role === 'secretary') {
            return view('demo1.secretary.dashboard');
        }

        // Dashboard pour patient
        if ($user->role === 'patient') {
            return view('demo1.patient.dashboard');
        }

        // Vérifier que l'utilisateur est un médecin
        if ($user->role !== 'doctor') {
            return redirect()->route('login');
        }

        // Rediriger selon le rôle du médecin
        if ($user->is_chief) {
            return $this->chiefDashboard();
        } else {
            return $this->doctorDashboard();
        }
    }

    /**
     * Dashboard pour médecin régulier (Praticien)
     * Affiche uniquement les statistiques et RDV du médecin connecté
     */
    private function doctorDashboard()
    {
        $doctorId = Auth::id();

        // ============================================
        // Statistiques Personnelles du Médecin
        // ============================================

        // Mes RDV du jour
        $myAppointmentsToday = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', today())
            ->count();

        // Mes consultations ce mois
        $myConsultationsThisMonth = Appointment::where('doctor_id', $doctorId)
            ->where('type', 'consultation')
            ->whereMonth('appointment_date', now()->month)
            ->whereYear('appointment_date', now()->year)
            ->count();

        // Mes patients vus (distincts avec status completed)
        $myPatientsSeen = Appointment::where('doctor_id', $doctorId)
            ->where('status', 'completed')
            ->distinct('patient_id')
            ->count('patient_id');

        // Mes RDV à venir (7 jours)
        $myUpcomingAppointments = Appointment::where('doctor_id', $doctorId)
            ->whereBetween('appointment_date', [today(), today()->addDays(7)])
            ->count();

        // ============================================
        // RDV du jour pour ce médecin (max 10)
        // ============================================
        $todayAppointments = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', today())
            ->with(['patient'])
            ->orderBy('appointment_time', 'asc')
            ->limit(10)
            ->get();

        // Compter le total des RDV du jour pour afficher "Voir tous"
        $totalTodayAppointments = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', today())
            ->count();

        return view('demo1.doctor.dashboard', [
            'myAppointmentsToday' => $myAppointmentsToday,
            'myConsultationsThisMonth' => $myConsultationsThisMonth,
            'myPatientsSeen' => $myPatientsSeen,
            'myUpcomingAppointments' => $myUpcomingAppointments,
            'todayAppointments' => $todayAppointments,
            'totalTodayAppointments' => $totalTodayAppointments,
        ]);
    }

    /**
     * Dashboard pour médecin chef (Directeur)
     * Affiche les statistiques globales et toutes les données
     */
    private function chiefDashboard()
    {
        // ============================================
        // Statistiques Globales
        // ============================================

        // RDV du jour (TOUS les médecins)
        $allAppointmentsToday = Appointment::whereDate('appointment_date', today())
            ->count();

        // Consultations ce mois (TOUTES)
        $allConsultationsThisMonth = Appointment::where('type', 'consultation')
            ->whereMonth('appointment_date', now()->month)
            ->whereYear('appointment_date', now()->year)
            ->count();

        // Demandes de service en attente
        $pendingRequests = ServiceRequest::where(function ($query) {
            $query->where('status', 'pending')
                ->orWhere('payment_status', 'pending');
        })->count();

        // Total patients
        $totalPatients = User::where('role', 'patient')->count();

        // Médecins actifs (réguliers, pas chef)
        $activeDoctors = User::where('role', 'doctor')
            ->where('is_active', true)
            ->where('is_chief', false)
            ->count();

        // Taux de complétion RDV
        $totalAppointments = Appointment::count();
        $completedAppointments = Appointment::where('status', 'completed')->count();
        $completionRate = $totalAppointments > 0 
            ? ($completedAppointments / $totalAppointments) * 100 
            : 0;

        // ============================================
        // RDV du jour (TOUS médecins, max 10)
        // ============================================
        $todayAppointments = Appointment::whereDate('appointment_date', today())
            ->with(['patient', 'doctor'])
            ->orderBy('appointment_time', 'asc')
            ->limit(10)
            ->get();

        // Compter le total des RDV du jour
        $totalTodayAppointments = Appointment::whereDate('appointment_date', today())
            ->count();

        // ============================================
        // Performance par médecin (médecins réguliers)
        // ============================================
        $doctors = User::where('role', 'doctor')
            ->where('is_chief', false)
            ->where('is_active', true)
            ->get();

        $doctorPerformance = $doctors->map(function ($doctor) {
            // RDV ce mois
            $appointmentsThisMonth = Appointment::where('doctor_id', $doctor->id)
                ->whereMonth('appointment_date', now()->month)
                ->whereYear('appointment_date', now()->year)
                ->count();

            // Consultations effectuées
            $consultations = Appointment::where('doctor_id', $doctor->id)
                ->where('type', 'consultation')
                ->whereMonth('appointment_date', now()->month)
                ->whereYear('appointment_date', now()->year)
                ->count();

            // Taux de complétion (ce médecin)
            $totalDoctorAppointments = Appointment::where('doctor_id', $doctor->id)->count();
            $completedDoctorAppointments = Appointment::where('doctor_id', $doctor->id)
                ->where('status', 'completed')
                ->count();
            $doctorCompletionRate = $totalDoctorAppointments > 0
                ? ($completedDoctorAppointments / $totalDoctorAppointments) * 100
                : 0;

            // Patients vus (distincts)
            $patientsSeen = Appointment::where('doctor_id', $doctor->id)
                ->where('status', 'completed')
                ->distinct('patient_id')
                ->count('patient_id');

            return [
                'id' => $doctor->id,
                'name' => $doctor->full_name,
                'speciality' => $doctor->speciality,
                'appointments_count' => $appointmentsThisMonth,
                'consultations_count' => $consultations,
                'completion_rate' => round($doctorCompletionRate, 1),
                'patients_count' => $patientsSeen,
            ];
        });

        // ============================================
        // 5 dernières demandes de service
        // ============================================
        $recentRequests = ServiceRequest::latest()
            ->with(['patient'])
            ->limit(5)
            ->get();

        // Total demandes pour lien "Voir tous"
        $totalRequests = ServiceRequest::count();

        return view('demo1.doctor.dashboard-chief', [
            'allAppointmentsToday' => $allAppointmentsToday,
            'allConsultationsThisMonth' => $allConsultationsThisMonth,
            'pendingRequests' => $pendingRequests,
            'totalPatients' => $totalPatients,
            'activeDoctors' => $activeDoctors,
            'completionRate' => round($completionRate, 1),
            'todayAppointments' => $todayAppointments,
            'totalTodayAppointments' => $totalTodayAppointments,
            'doctorPerformance' => $doctorPerformance,
            'recentRequests' => $recentRequests,
            'totalRequests' => $totalRequests,
        ]);
    }
}
