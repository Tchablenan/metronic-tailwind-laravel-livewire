<?php

namespace App\Http\Controllers\Demo1;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Notifications\ServiceRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SecretaryServiceRequestController extends Controller
{
    /**
     * Lister les demandes de service (secrétaire) avec filtres et statistiques
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', ServiceRequest::class);

        // =========================================
        // Récupérer les paramètres de filtre
        // =========================================
        $search = $request->input('search');
        $status = $request->input('status');
        $serviceType = $request->input('service_type');
        $urgency = $request->input('urgency');
        $paymentStatus = $request->input('payment_status');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $hasInsurance = $request->input('has_insurance');
        $hasMedicalData = $request->input('has_medical_data');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        // =========================================
        // Construire la requête avec filtres
        // =========================================
        $query = ServiceRequest::query();

        // Recherche globale
        if ($search) {
            $query->search($search);
        }

        // Filtres individuels
        if ($status) {
            $query->byStatus($status);
        }
        if ($serviceType) {
            $query->byServiceType($serviceType);
        }
        if ($urgency) {
            $query->byUrgency($urgency);
        }
        if ($paymentStatus) {
            $query->byPaymentStatus($paymentStatus);
        }
        if ($hasInsurance) {
            $query->hasInsurance();
        }
        if ($hasMedicalData) {
            $query->hasMedicalData();
        }

        // Filtre de date
        if ($dateFrom || $dateTo) {
            $query->byDateRange($dateFrom, $dateTo);
        }

        // Tri
        $validSortFields = ['created_at', 'updated_at', 'payment_amount', 'urgency', 'status'];
        if (in_array($sortBy, $validSortFields)) {
            $query->orderBy($sortBy, strtolower($sortOrder) === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderByDesc('created_at');
        }

        // =========================================
        // Calculer les statistiques AVANT pagination
        // =========================================
        $totalQuery = clone $query;
        $statistics = [
            'pending' => (clone $totalQuery)->byStatus('pending')->count(),
            'in_progress' => (clone $totalQuery)->byStatus('in_progress')->count(),
            'completed' => (clone $totalQuery)->byStatus('completed')->count(),
            'total_amount' => (clone $totalQuery)->sum('payment_amount') ?? 0,
            'total_with_medical_data' => (clone $totalQuery)->hasMedicalData()->count(),
            'total' => $totalQuery->count(),
        ];

        // Calculer le pourcentage de demandes avec données médicales
        $statistics['medical_data_percentage'] = $statistics['total'] > 0
            ? round(($statistics['total_with_medical_data'] / $statistics['total']) * 100, 1)
            : 0;

        // =========================================
        // Paginer les résultats
        // =========================================
        $serviceRequests = $query->paginate(10);

        // =========================================
        // Options pour les filtres (pour dropdowns)
        // =========================================
        $statusOptions = [
            'pending' => 'En Attente',
            'in_progress' => 'En Traitement',
            'completed' => 'Complétée',
            'cancelled' => 'Annulée',
        ];

        $serviceTypeOptions = [
            'appointment' => 'Rendez-vous',
            'home_visit' => 'Visite à domicile',
            'emergency' => 'Urgence',
            'transport' => 'Transport',
            'consultation' => 'Consultation',
        ];

        $urgencyOptions = [
            'low' => 'Basse',
            'medium' => 'Moyenne',
            'high' => 'Haute',
        ];

        $paymentStatusOptions = [
            'pending' => 'En Attente',
            'paid' => 'Payée',
            'partial' => 'Partiellement Payée',
            'overdue' => 'En Retard',
        ];

        return view('demo1.secretary.service-requests.index', compact(
            'serviceRequests',
            'statistics',
            'search',
            'status',
            'serviceType',
            'urgency',
            'paymentStatus',
            'dateFrom',
            'dateTo',
            'hasInsurance',
            'hasMedicalData',
            'statusOptions',
            'serviceTypeOptions',
            'urgencyOptions',
            'paymentStatusOptions'
        ));
    }

    /**
     * Afficher une demande de service
     */
    public function show(ServiceRequest $serviceRequest)
    {
        $this->authorize('view', $serviceRequest);

        return view('demo1.secretary.service-requests.show', compact('serviceRequest'));
    }

    /**
     * Formulaire de création d'une ServiceRequest
     */
    public function create()
    {
        $this->authorize('create', ServiceRequest::class);

        return view('demo1.secretary.service-requests.create');
    }

    /**
     * Enregistrer une nouvelle ServiceRequest créée par la secrétaire
     */
    public function store(Request $request)
    {
        $this->authorize('create', ServiceRequest::class);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'service_type' => 'required|in:appointment,home_visit,emergency,transport,consultation',
            'urgency' => 'required|in:low,medium,high',
            'message' => 'nullable|string|max:2000',
            'preferred_date' => 'nullable|date|after_or_equal:today',
            'preferred_time' => 'nullable|date_format:H:i',
            'payment_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,mobile_money,bank_transfer',

            // ============================================
            // 🏥 TRIAGE INITIAL (tous optionnels)
            // ============================================
            'temperature' => 'nullable|numeric|min:30|max:45',
            'blood_pressure_systolic' => 'nullable|integer|min:50|max:250',
            'blood_pressure_diastolic' => 'nullable|integer|min:30|max:150',
            'weight' => 'nullable|numeric|min:0|max:500',
            'height' => 'nullable|numeric|min:0|max:300',
            'known_allergies' => 'nullable|string|max:1000',
            'current_medications' => 'nullable|string|max:1000',

            // ============================================
            // 🛡️ ASSURANCE (conditionnels)
            // ============================================
            'has_insurance' => 'nullable|boolean',
            'insurance_company' => 'nullable|required_if:has_insurance,1|string|max:100',
            'insurance_policy_number' => 'nullable|required_if:has_insurance,1|string|max:100',
            'insurance_coverage_rate' => 'nullable|integer|min:0|max:100',
            'insurance_ceiling' => 'nullable|numeric|min:0',
            'insurance_expiry_date' => 'nullable|date|after:today',

            // ============================================
            // 📋 EXAMENS (conditionnels)
            // ============================================
            'has_previous_exams' => 'nullable|boolean',
            'previous_exam_type' => 'nullable|required_if:has_previous_exams,1|in:laboratory,imaging,ecg,covid,checkup,other',
            'previous_exam_name' => 'nullable|required_if:has_previous_exams,1|string|max:255',
            'previous_exam_facility' => 'nullable|required_if:has_previous_exams,1|string|max:255',
            'previous_exam_date' => 'nullable|date|before_or_equal:today',
            'previous_exam_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'phone_number.required' => 'Le téléphone est obligatoire.',
            'service_type.required' => 'Le type de service est obligatoire.',
            'urgency.required' => 'Le niveau d\'urgence est obligatoire.',
            'payment_amount.required' => 'Le montant est obligatoire.',
            'payment_amount.numeric' => 'Le montant doit être un nombre.',
            'payment_method.required' => 'La méthode de paiement est obligatoire.',

            // Messages d'erreur assurance
            'insurance_company.required_if' => 'La compagnie d\'assurance est obligatoire si le patient est assuré.',
            'insurance_policy_number.required_if' => 'Le numéro de police est obligatoire si le patient est assuré.',

            // Messages d'erreur examens
            'previous_exam_type.required_if' => 'Le type d\'examen est obligatoire si des examens ont été effectués.',
            'previous_exam_name.required_if' => 'Le nom de l\'examen est obligatoire si des examens ont été effectués.',
            'previous_exam_facility.required_if' => 'L\'établissement est obligatoire si des examens ont été effectués.',
            'previous_exam_file.mimes' => 'Le fichier doit être au format PDF, JPG ou PNG.',
            'previous_exam_file.max' => 'Le fichier ne doit pas dépasser 5 Mo.',
        ]);

        // ============================================
        // GESTION UPLOAD DU FICHIER D'EXAMEN
        // ============================================
        if ($request->hasFile('previous_exam_file')) {
            $file = $request->file('previous_exam_file');
            $path = $file->store('exam_results', 'public');
            $validated['previous_exam_file_path'] = $path;
        }

        DB::beginTransaction();

        try {
            // Compléter les données
            $validated['status'] = 'pending';
            $validated['payment_status'] = 'paid'; // Déjà payé au cabinet
            $validated['created_by_secretary'] = true; // Flag pour identifier la source
            $validated['handled_by_secretary'] = Auth::id();
            $validated['paid_at'] = now();
            $validated['handled_by'] = Auth::id();
            $validated['handled_at'] = now();

            $serviceRequest = ServiceRequest::create($validated);

            // Notifier le médecin chef
            $chief = User::where('role', 'doctor')
                ->where('is_chief', true)
                ->where('is_active', true)
                ->first();

            if ($chief) {
                $chief->notify(new ServiceRequestNotification($serviceRequest, 'forwarded'));
            }

            DB::commit();

            return redirect()->route('secretary.service-requests.show', $serviceRequest)
                ->with('success', 'Demande créée avec succès. Le médecin chef a été notifié.');
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Erreur création ServiceRequest par secrétaire: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Erreur lors de la création : ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(ServiceRequest $serviceRequest)
    {
        $this->authorize('update', $serviceRequest);

        // Vérifier que le statut permet l'édition
        if (!$serviceRequest->canBeEdited()) {
            return redirect()->route('secretary.service-requests.show', $serviceRequest)
                ->with('error', 'Cette demande ne peut plus être modifiée.');
        }

        return view('demo1.secretary.service-requests.edit', compact('serviceRequest'));
    }

    /**
     * Enregistrer les modifications
     */
    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        $this->authorize('update', $serviceRequest);

        // Vérifier que le statut permet l'édition
        if (!$serviceRequest->canBeEdited()) {
            return redirect()->route('secretary.service-requests.show', $serviceRequest)
                ->with('error', 'Cette demande ne peut plus être modifiée.');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'service_type' => 'required|in:appointment,home_visit,emergency,transport,consultation',
            'urgency' => 'required|in:low,medium,high',
            'message' => 'nullable|string|max:2000',
            'preferred_date' => 'nullable|date|after_or_equal:today',
            'preferred_time' => 'nullable|date_format:H:i',
            'payment_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,mobile_money,bank_transfer',

            // ============================================
            // 🏥 TRIAGE INITIAL (tous optionnels)
            // ============================================
            'temperature' => 'nullable|numeric|min:30|max:45',
            'blood_pressure_systolic' => 'nullable|integer|min:50|max:250',
            'blood_pressure_diastolic' => 'nullable|integer|min:30|max:150',
            'weight' => 'nullable|numeric|min:0|max:500',
            'height' => 'nullable|numeric|min:0|max:300',
            'known_allergies' => 'nullable|string|max:1000',
            'current_medications' => 'nullable|string|max:1000',

            // ============================================
            // 🛡️ ASSURANCE (conditionnels)
            // ============================================
            'has_insurance' => 'nullable|boolean',
            'insurance_company' => 'nullable|required_if:has_insurance,1|string|max:100',
            'insurance_policy_number' => 'nullable|required_if:has_insurance,1|string|max:100',
            'insurance_coverage_rate' => 'nullable|integer|min:0|max:100',
            'insurance_ceiling' => 'nullable|numeric|min:0',
            'insurance_expiry_date' => 'nullable|date|after:today',

            // ============================================
            // 📋 EXAMENS (conditionnels)
            // ============================================
            'has_previous_exams' => 'nullable|boolean',
            'previous_exam_type' => 'nullable|required_if:has_previous_exams,1|in:laboratory,imaging,ecg,covid,checkup,other',
            'previous_exam_name' => 'nullable|required_if:has_previous_exams,1|string|max:255',
            'previous_exam_facility' => 'nullable|required_if:has_previous_exams,1|string|max:255',
            'previous_exam_date' => 'nullable|date|before_or_equal:today',
            'previous_exam_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'phone_number.required' => 'Le téléphone est obligatoire.',
            'service_type.required' => 'Le type de service est obligatoire.',
            'urgency.required' => 'Le niveau d\'urgence est obligatoire.',
            'payment_amount.required' => 'Le montant est obligatoire.',
            'payment_amount.numeric' => 'Le montant doit être un nombre.',
            'payment_method.required' => 'La méthode de paiement est obligatoire.',

            // Messages d'erreur assurance
            'insurance_company.required_if' => 'La compagnie d\'assurance est obligatoire si le patient est assuré.',
            'insurance_policy_number.required_if' => 'Le numéro de police est obligatoire si le patient est assuré.',

            // Messages d'erreur examens
            'previous_exam_type.required_if' => 'Le type d\'examen est obligatoire si des examens ont été effectués.',
            'previous_exam_name.required_if' => 'Le nom de l\'examen est obligatoire si des examens ont été effectués.',
            'previous_exam_facility.required_if' => 'L\'établissement est obligatoire si des examens ont été effectués.',
            'previous_exam_file.mimes' => 'Le fichier doit être au format PDF, JPG ou PNG.',
            'previous_exam_file.max' => 'Le fichier ne doit pas dépasser 5 Mo.',
        ]);

        // ============================================
        // GESTION UPLOAD DU FICHIER D'EXAMEN
        // ============================================
        if ($request->hasFile('previous_exam_file')) {
            $file = $request->file('previous_exam_file');
            $path = $file->store('exam_results', 'public');
            $validated['previous_exam_file_path'] = $path;
        }

        DB::beginTransaction();

        try {
            $serviceRequest->update($validated);

            DB::commit();

            return redirect()->route('secretary.service-requests.show', $serviceRequest)
                ->with('success', 'Demande modifiée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Erreur modification ServiceRequest: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Erreur lors de la modification : ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Marquer comme payé
     */
    public function markPaid(Request $request, ServiceRequest $serviceRequest)
    {
        $request->validate([
            'payment_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,mobile_money,card,insurance',
        ], [
            'payment_amount.required' => 'Le montant est obligatoire.',
            'payment_amount.numeric' => 'Le montant doit être un nombre.',
            'payment_amount.min' => 'Le montant doit être positif.',
            'payment_method.required' => 'La méthode de paiement est obligatoire.',
        ]);

        $serviceRequest->update([
            'payment_status' => 'paid',
            'payment_amount' => $request->payment_amount,
            'payment_method' => $request->payment_method,
            'paid_at' => now(),
            'handled_by' => Auth::id(),
            'handled_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Paiement enregistré avec succès.');
    }

    /**
     * Send to chief doctor
     */
    public function sendToDoctor(ServiceRequest $serviceRequest)
    {
        // Check payment
        if ($serviceRequest->payment_status !== 'paid') {
            return redirect()->back()->with('error', 'Le patient doit d\'abord payer avant d\'envoyer au médecin.');
        }

        $serviceRequest->update([
            'sent_to_doctor' => true,
            'sent_to_doctor_at' => now(),
            'sent_by' => Auth::id(),
            'status' => 'contacted',
        ]);

        // Notify chief doctors
        $chiefDoctors = User::where('role', 'doctor')
            ->where('is_chief', true)
            ->where('is_active', true)
            ->get();

        foreach ($chiefDoctors as $doctor) {
            $doctor->notify(new \App\Notifications\ServiceRequestNotification($serviceRequest, 'forwarded'));
        }

        return redirect()->back()->with('success', 'Demande envoyée au médecin chef avec succès. Les notifications ont été envoyées.');
    }

    /**
     * Cancel doctor notification (if error)
     */
    public function cancelSendToDoctor(ServiceRequest $serviceRequest)
    {
        $serviceRequest->update([
            'sent_to_doctor' => false,
            'sent_to_doctor_at' => null,
            'sent_by' => null,
        ]);

        return redirect()->back()->with('success', 'Envoi au médecin annulé.');
    }
}
