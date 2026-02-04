<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'service_type',
        'message',
        'preferred_date',
        'preferred_time',
        'urgency',
        'status',
        // ✅ AJOUTE CES CHAMPS DE PAIEMENT
        'payment_status',
        'payment_amount',
        'payment_method',
        'paid_at',
        'sent_to_doctor',
        'sent_to_doctor_at',
        'sent_by',
        // Champs existants
        'patient_id',
        'appointment_id',
        'handled_by',
        'handled_at',
        'internal_notes',
        // Champs secrétaire
        'created_by_secretary',
        'handled_by_secretary',

        // ============================================
        // 🏥 GROUPE 1 : TRIAGE INITIAL
        // ============================================
        'temperature',
        'blood_pressure_systolic',
        'blood_pressure_diastolic',
        'weight',
        'height',
        'known_allergies',
        'current_medications',

        // ============================================
        // 🛡️ GROUPE 2 : ASSURANCE
        // ============================================
        'has_insurance',
        'insurance_company',
        'insurance_policy_number',
        'insurance_coverage_rate',
        'insurance_ceiling',
        'insurance_expiry_date',

        // ============================================
        // 📋 GROUPE 3 : EXAMENS ANTÉRIEURS
        // ============================================
        'has_previous_exams',
        'previous_exam_type',
        'previous_exam_name',
        'previous_exam_facility',
        'previous_exam_date',
        'previous_exam_file_path',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'handled_at' => 'datetime',
        'paid_at' => 'datetime',              // ✅ AJOUTE
        'sent_to_doctor_at' => 'datetime',    // ✅ AJOUTE
        'sent_to_doctor' => 'boolean',        // ✅ AJOUTE
        'created_by_secretary' => 'boolean',  // ✅ AJOUTE
        'payment_amount' => 'decimal:2',      // ✅ AJOUTE

        // 🏥 TRIAGE
        'temperature' => 'decimal:1',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',

        // 🛡️ ASSURANCE
        'has_insurance' => 'boolean',
        'insurance_coverage_rate' => 'integer',
        'insurance_ceiling' => 'decimal:2',
        'insurance_expiry_date' => 'date',

        // 📋 EXAMENS
        'has_previous_exams' => 'boolean',
        'previous_exam_date' => 'date',
    ];

    // ============================================
    // 🏥 ACCESSEURS ET CALCULS MÉDICAUX
    // ============================================

    /**
     * Retourne la tension formatée "X/Y mmHg"
     */
    public function getFormattedBloodPressureAttribute(): ?string
    {
        if ($this->blood_pressure_systolic && $this->blood_pressure_diastolic) {
            return "{$this->blood_pressure_systolic}/{$this->blood_pressure_diastolic} mmHg";
        }
        return null;
    }

    /**
     * Calcule l'IMC (Indice de Masse Corporelle)
     * Formule : poids (kg) / (taille en m)²
     */
    public function getBmiAttribute(): ?float
    {
        if (!$this->weight || !$this->height || $this->height <= 0) {
            return null;
        }
        $heightInMeters = $this->height / 100;
        return round($this->weight / ($heightInMeters ** 2), 2);
    }

    /**
     * Vérifie si le fichier d'examen existe
     */
    public function hasExamFile(): bool
    {
        if (!$this->previous_exam_file_path) {
            return false;
        }
        return \Storage::disk('public')->exists($this->previous_exam_file_path);
    }

    /**
     * Retourne l'URL publique du fichier d'examen
     */
    public function getExamFileUrlAttribute(): ?string
    {
        if ($this->hasExamFile()) {
            return \Storage::url($this->previous_exam_file_path);
        }
        return null;
    }

    /**
     * Retourne le label du type d'examen
     */
    public function getPreviousExamTypeLabel(): ?string
    {
        if (!$this->previous_exam_type) {
            return null;
        }
        return match($this->previous_exam_type) {
            'laboratory' => '🧪 Analyses de laboratoire',
            'imaging' => '📸 Imagerie médicale',
            'ecg' => '💓 Électrocardiogramme',
            'covid' => '🦠 Test COVID-19',
            'checkup' => '✅ Bilan de santé',
            'other' => '📋 Autre examen',
            default => $this->previous_exam_type,
        };
    }

    // ============================================
    // RELATIONS
    // ============================================

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Rendez-vous créé (si converti)
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Personne qui a traité la demande
     */
    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    /**
     * Secrétaire qui a envoyé au médecin (✅ AJOUTE)
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    // ============================================
    // 🔍 SCOPES POUR FILTRAGE
    // ============================================

    /**
     * Filtrer par statut
     */
    public function scopeByStatus($query, $status)
    {
        if (!$status) return $query;
        return $query->where('status', $status);
    }

    /**
     * Filtrer par type de service
     */
    public function scopeByServiceType($query, $serviceType)
    {
        if (!$serviceType) return $query;
        return $query->where('service_type', $serviceType);
    }

    /**
     * Filtrer par urgence
     */
    public function scopeByUrgency($query, $urgency)
    {
        if (!$urgency) return $query;
        return $query->where('urgency', $urgency);
    }

    /**
     * Filtrer par statut paiement
     */
    public function scopeByPaymentStatus($query, $status)
    {
        if (!$status) return $query;
        return $query->where('payment_status', $status);
    }

    /**
     * Filtrer: patient assuré
     */
    public function scopeHasInsurance($query)
    {
        return $query->where('has_insurance', true);
    }

    /**
     * Filtrer: avec données médicales
     */
    public function scopeHasMedicalData($query)
    {
        return $query->where(function($q) {
            $q->whereNotNull('temperature')
                ->orWhereNotNull('blood_pressure_systolic')
                ->orWhereNotNull('weight')
                ->orWhereNotNull('height')
                ->orWhereNotNull('known_allergies')
                ->orWhereNotNull('current_medications')
                ->orWhereNotNull('has_insurance')
                ->orWhereNotNull('has_previous_exams');
        });
    }

    /**
     * Recherche par texte (nom patient, email, téléphone, id)
     */
    public function scopeSearch($query, $search)
    {
        if (!$search) return $query;

        return $query->where(function($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone_number', 'like', "%{$search}%")
                ->orWhere('id', '=', $search);
        });
    }

    /**
     * Filtrer par date (depuis et jusqu'à)
     */
    public function scopeByDateRange($query, $fromDate = null, $toDate = null)
    {
        if ($fromDate) {
            $query = $query->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $query = $query->whereDate('created_at', '<=', $toDate);
        }
        return $query;
    }

    // ============================================
    // ✅ MÉTHODES UTILITAIRES
    // ============================================

    /**
     * Vérifier si la demande peut être éditée
     */
    public function canBeEdited(): bool
    {
        // Seulement pendant le statut "pending" ou "in_progress"
        return in_array($this->status, ['pending', 'in_progress']);
    }

    /**
     * Secrétaire qui a créé la demande (✅ AJOUTE)
     */
    public function creatingSecretary(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by_secretary');
    }

    /**
     * Nom complet
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Label du statut de paiement (✅ AJOUTE)
     */
    public function getPaymentStatusLabelAttribute(): string
    {
        return match($this->payment_status) {
            'unpaid' => 'Non payé',
            'paid' => 'Payé',
            'refunded' => 'Remboursé',
            default => $this->payment_status ?? 'Non défini',
        };
    }

    /**
     * Label de la méthode de paiement (✅ AJOUTE)
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        return match($this->payment_method) {
            'cash' => 'Espèces',
            'mobile_money' => 'Mobile Money',
            'card' => 'Carte bancaire',
            'insurance' => 'Assurance',
            default => $this->payment_method ?? 'Non défini',
        };
    }

    /**
     * Scope pour les demandes en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope pour les demandes non traitées
     */
    public function scopeUnhandled($query)
    {
        return $query->whereNull('handled_at');
    }

    /**
     * Scope pour les demandes payées (✅ AJOUTE)
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Scope pour les demandes non payées (✅ AJOUTE)
     */
    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'unpaid');
    }

    /**
     * Scope pour les demandes envoyées au médecin (✅ AJOUTE)
     */
    public function scopeSentToDoctor($query)
    {
        return $query->where('sent_to_doctor', true);
    }

    /**
     * Scope pour les demandes non envoyées (✅ AJOUTE)
     */
    public function scopeNotSentToDoctor($query)
    {
        return $query->where('sent_to_doctor', false);
    }
}
