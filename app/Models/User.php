<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'avatar_url',
        'role',
        'is_chief',
        'speciality',
        'license_number',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_chief' => 'boolean',
        ];
    }

    /**
     * ============================================
     * ACCESSORS & MUTATORS
     * ============================================
     */

    /**
     * Get user's full name
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get user's initials
     */

    public function getAvatarUrlAttribute()
    {
        if ($this->attributes['avatar_url']) {
            return asset('storage/' . $this->attributes['avatar_url']);
        }

        $name = urlencode($this->first_name . ' ' . $this->last_name);
        $colors = [
            'doctor' => '1075B9',
            'nurse' => '22c55e',
            'secretary' => '06b6d4',
            'patient' => 'f59e0b',
            'partner' => '5B5FED',
            'home_care_member' => '6b7280',
        ];
        $color = $colors[$this->role] ?? '6366f1';

        return "https://ui-avatars.com/api/?name={$name}&color=ffffff&background={$color}&size=200&bold=true";
    }

    /**
     * ============================================
     * SCOPES (Filtres utiles)
     * ============================================
     */

    /**
     * Scope: Utilisateurs actifs uniquement
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Utilisateurs inactifs
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope: Filtrer par rôle
     */
    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope: Doctors uniquement
     */
    public function scopeDoctors($query)
    {
        return $query->where('role', 'doctor');
    }

    /**
     * Scope: Nurses uniquement
     */
    public function scopeNurses($query)
    {
        return $query->where('role', 'nurse');
    }

    /**
     * Scope: Secretaries uniquement
     */
    public function scopeSecretaries($query)
    {
        return $query->where('role', 'secretary');
    }

    /**
     * Scope: Patients uniquement
     */
    public function scopePatients($query)
    {
        return $query->where('role', 'patient');
    }

    /**
     * Scope: Partners uniquement
     */
    public function scopePartners($query)
    {
        return $query->where('role', 'partner');
    }

    /**
     * Scope: Home Care Team Members uniquement
     */
    public function scopeHomeCareMembers($query)
    {
        return $query->where('role', 'home_care_member');
    }

    /**
     * Scope: Admins uniquement
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope: Recherche par nom ou email
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where('first_name', 'like', "%{$search}%")
            ->orWhere('last_name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%");
    }

    /**
     * Scope: Médecin chef uniquement
     */
    public function scopeChief($query)
    {
        return $query->where('role', 'doctor')->where('is_chief', true);
    }

    /**
     * Scope: Médecins réguliers (hors chef)
     */
    public function scopeRegularDoctors($query)
    {
        return $query->where('role', 'doctor')->where('is_chief', false);
    }

    /**
     * ============================================
     * UTILITY METHODS
     * ============================================
     */

    /**
     * Vérifier si l'utilisateur est admin (Médecin Chef)
     * Note: Le doctor est le super-admin du système
     */
    public function isAdmin(): bool
    {
        return $this->role === 'doctor' && $this->is_chief === true;
    }

    /**
     * Vérifier si l'utilisateur est le médecin chef
     */
    public function isChief(): bool
    {
        return $this->role === 'doctor' && $this->is_chief === true;
    }

    /**
     * Vérifier si l'utilisateur est un médecin régulier (pas le chef)
     */
    public function isRegularDoctor(): bool
    {
        return $this->role === 'doctor' && $this->is_chief === false;
    }

    /**
     * Vérifier si l'utilisateur est un doctor (Médecin Chef/Admin)
     */
    public function isDoctor(): bool
    {
        return $this->role === 'doctor';
    }

    /**
     * Vérifier si l'utilisateur est une nurse
     */
    public function isNurse(): bool
    {
        return $this->role === 'nurse';
    }

    /**
     * Vérifier si l'utilisateur est un secretary
     */
    public function isSecretary(): bool
    {
        return $this->role === 'secretary';
    }

    /**
     * Vérifier si l'utilisateur est un patient
     */
    public function isPatient(): bool
    {
        return $this->role === 'patient';
    }

    /**
     * Vérifier si l'utilisateur est un partner
     */
    public function isPartner(): bool
    {
        return $this->role === 'partner';
    }

    /**
     * Vérifier si l'utilisateur est home care member
     */
    public function isHomeCareTeamMember(): bool
    {
        return $this->role === 'home_care_member';
    }

    /**
     * Vérifier si l'utilisateur est personnel médical (doctor ou nurse)
     */
    public function isMedicalStaff(): bool
    {
        return in_array($this->role, ['doctor', 'nurse']);
    }

    /**
     * Vérifier si l'utilisateur est staff administratif
     */
    public function isAdministrativeStaff(): bool
    {
        return in_array($this->role, ['secretary', 'admin']);
    }

    // Dans la classe User, ajoute ces méthodes

    /**
     * Rendez-vous en tant que patient
     */
    public function appointmentsAsPatient()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    /**
     * Rendez-vous en tant que docteur
     */
    public function appointmentsAsDoctor()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    /**
     * Rendez-vous en tant qu'infirmier
     */
    public function appointmentsAsNurse()
    {
        return $this->hasMany(Appointment::class, 'nurse_id');
    }

    /**
     * Tous les rendez-vous (pour le personnel médical)
     */
    public function allAppointments()
    {
        return Appointment::where('doctor_id', $this->id)
            ->orWhere('nurse_id', $this->id)
            ->orWhere('patient_id', $this->id);
    }


}
;
