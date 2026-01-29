<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'nurse_id',
        //'service_request_id',
        'appointment_date',
        'appointment_time',
        'duration',
        'type',
        'status',
        'reason',
        'notes',
        'patient_notes',
        'reminder_sent',
        'reminder_sent_at',
        'location',
        'price',
        'is_emergency',
        'cancelled_by',
        'cancellation_reason',
        'cancelled_at',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime:H:i',
        'reminder_sent' => 'boolean',
        'reminder_sent_at' => 'datetime',
        'is_emergency' => 'boolean',
        'price' => 'decimal:2',
        'cancelled_at' => 'datetime',
    ];

    protected $appends = [
        'formatted_date',
        'formatted_time',
        'full_datetime',
        'status_label',
        'type_label',
        'status_color',
    ];

    // ==========================================
    // RELATIONS
    // ==========================================

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function nurse()
    {
        return $this->belongsTo(User::class, 'nurse_id');
    }

    //public function serviceRequest()
    //{
      //  return $this->belongsTo(ServiceRequest::class);
    //}

    // ==========================================
    // ACCESSORS
    // ==========================================

    public function getFormattedDateAttribute()
    {
        //return $this->appointment_date->format('d/m/Y');
        return substr($this->appointment_time, 0, 5);
    }

    public function getFormattedTimeAttribute()
    {
        return Carbon::parse($this->appointment_time)->format('H:i');
    }

    public function getFullDatetimeAttribute()
    {
        return $this->appointment_date->format('d/m/Y') . ' à ' . $this->formatted_time;
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'scheduled' => 'Prévu',
            'confirmed' => 'Confirmé',
            'in_progress' => 'En cours',
            'completed' => 'Terminé',
            'cancelled' => 'Annulé',
            'no_show' => 'Absent',
            default => 'Inconnu',
        };
    }

    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'consultation' => 'Consultation',
            'followup' => 'Suivi',
            'exam' => 'Examen',
            'emergency' => 'Urgence',
            'vaccination' => 'Vaccination',
            'home_visit' => 'Visite à domicile',
            'telehealth' => 'Téléconsultation',
            default => 'Autre',
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'scheduled' => 'blue',
            'confirmed' => 'green',
            'in_progress' => 'yellow',
            'completed' => 'gray',
            'cancelled' => 'red',
            'no_show' => 'orange',
            default => 'gray',
        };
    }

    public function getTypeColorAttribute()
    {
        return match($this->type) {
            'consultation' => 'blue',
            'followup' => 'purple',
            'exam' => 'cyan',
            'emergency' => 'red',
            'vaccination' => 'green',
            'home_visit' => 'orange',
            'telehealth' => 'indigo',
            default => 'gray',
        };
    }

    // ==========================================
    // SCOPES
    // ==========================================

    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', now()->toDateString())
                    ->whereIn('status', ['scheduled', 'confirmed'])
                    ->orderBy('appointment_date')
                    ->orderBy('appointment_time');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', today());
    }

    public function scopeByDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeByPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeEmergency($query)
    {
        return $query->where('is_emergency', true);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    // ==========================================
    // MÉTHODES UTILITAIRES
    // ==========================================

    public function isPast()
    {
        return $this->appointment_date->isPast();
    }

    public function isToday()
    {
        return $this->appointment_date->isToday();
    }

    public function isTomorrow()
    {
        return $this->appointment_date->isTomorrow();
    }

    public function canBeModified()
    {
        return !in_array($this->status, ['completed', 'cancelled']) && !$this->isPast();
    }

    public function canBeCancelled()
    {
        return !in_array($this->status, ['completed', 'cancelled']);
    }

    public function canBeConfirmed()
    {
        return $this->status === 'scheduled' && !$this->isPast();
    }

    public function canBeStarted()
    {
        return $this->status === 'confirmed' && ($this->isToday() || $this->isPast());
    }

    public function canBeCompleted()
    {
        return $this->status === 'in_progress';
    }

    /**
     * Obtenir l'heure de fin du rendez-vous
     */
    public function getEndTime()
    {
        $time = \Carbon\Carbon::createFromFormat('H:i:s', $this->appointment_time);
        $duration = (int) $this->duration;
        return $time->addMinutes($duration);
    }

    /**
     * Vérifier si le rendez-vous est en conflit avec un autre
     */
    public function hasConflictWith($doctorId, $date, $time, $duration)
    {
        $startTime = Carbon::parse($time);
        $endTime = $startTime->copy()->addMinutes($duration);

        return self::where('doctor_id', $doctorId)
            ->where('appointment_date', $date)
            ->whereIn('status', ['scheduled', 'confirmed', 'in_progress'])
            ->where('id', '!=', $this->id ?? 0)
            ->where(function($query) use ($startTime, $endTime) {
                $query->whereBetween('appointment_time', [$startTime, $endTime])
                      ->orWhere(function($q) use ($startTime) {
                          $q->where('appointment_time', '<=', $startTime)
                            ->whereRaw('DATE_ADD(appointment_time, INTERVAL duration MINUTE) > ?', [$startTime]);
                      });
            })
            ->exists();
    }

    /**
     * Marquer comme confirmé
     */
    public function confirm()
    {
        if ($this->canBeConfirmed()) {
            $this->update(['status' => 'confirmed']);
            return true;
        }
        return false;
    }

    /**
     * Démarrer le rendez-vous
     */
    public function start()
    {
        if ($this->canBeStarted()) {
            $this->update(['status' => 'in_progress']);
            return true;
        }
        return false;
    }

    /**
     * Terminer le rendez-vous
     */
    public function complete($notes = null)
    {
        if ($this->canBeCompleted()) {
            $this->update([
                'status' => 'completed',
                'notes' => $notes ?? $this->notes,
            ]);
            return true;
        }
        return false;
    }

    /**
     * Annuler le rendez-vous
     */
    public function cancel($reason, $cancelledBy)
    {
        if ($this->canBeCancelled()) {
            $this->update([
                'status' => 'cancelled',
                'cancellation_reason' => $reason,
                'cancelled_by' => $cancelledBy,
                'cancelled_at' => now(),
            ]);
            return true;
        }
        return false;
    }

    /**
     * Marquer le patient comme absent
     */
    public function markAsNoShow()
    {
        $this->update(['status' => 'no_show']);
    }
}
