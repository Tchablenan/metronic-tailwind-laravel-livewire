<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceRequest extends Model
{
    use HasFactory;

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
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'handled_at' => 'datetime',
        'paid_at' => 'datetime',              // ✅ AJOUTE
        'sent_to_doctor_at' => 'datetime',    // ✅ AJOUTE
        'sent_to_doctor' => 'boolean',        // ✅ AJOUTE
        'created_by_secretary' => 'boolean',  // ✅ AJOUTE
        'payment_amount' => 'decimal:2',      // ✅ AJOUTE
    ];

    /**
     * Patient lié (si converti)
     */
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
