<?php

namespace App\Enums;

enum AppointmentType: string
{
    case CONSULTATION = 'consultation';
    case FOLLOWUP = 'followup';
    case EXAM = 'exam';
    case EMERGENCY = 'emergency';
    case VACCINATION = 'vaccination';
    case HOME_VISIT = 'home_visit';
    case TELEHEALTH = 'telehealth';

    public function label(): string
    {
        return match ($this) {
            self::CONSULTATION => 'Consultation',
            self::FOLLOWUP => 'Suivi',
            self::EXAM => 'Examen',
            self::EMERGENCY => 'Urgence',
            self::VACCINATION => 'Vaccination',
            self::HOME_VISIT => 'Visite à domicile',
            self::TELEHEALTH => 'Téléconsultation',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::CONSULTATION => 'ki-filled ki-stethoscope',
            self::FOLLOWUP => 'ki-filled ki-repeat',
            self::EXAM => 'ki-filled ki-microscope',
            self::EMERGENCY => 'ki-filled ki-warning',
            self::VACCINATION => 'ki-filled ki-syringe',
            self::HOME_VISIT => 'ki-filled ki-home',
            self::TELEHEALTH => 'ki-filled ki-video-camera',
        };
    }

    /**
     * Get all types as key-value array for select fields
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }
        return $options;
    }
}
