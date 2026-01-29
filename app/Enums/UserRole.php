<?php

namespace App\Enums;

enum UserRole: string
{
    case DOCTOR = 'doctor';
    case NURSE = 'nurse';
    case SECRETARY = 'secretary';
    case PATIENT = 'patient';
    case PARTNER = 'partner';
    case HOME_CARE_MEMBER = 'home_care_member';

    public function label(): string
    {
        return match ($this) {
            self::DOCTOR => 'Médecin',
            self::NURSE => 'Infirmier',
            self::SECRETARY => 'Secrétaire',
            self::PATIENT => 'Patient',
            self::PARTNER => 'Partenaire',
            self::HOME_CARE_MEMBER => 'Aide à domicile',
        };
    }

    public function avatarColor(): string
    {
        return match ($this) {
            self::DOCTOR => '1075B9',
            self::NURSE => '22c55e',
            self::SECRETARY => '06b6d4',
            self::PATIENT => 'f59e0b',
            self::PARTNER => '5B5FED',
            self::HOME_CARE_MEMBER => '6b7280',
        };
    }

    /**
     * Get all roles as key-value array
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
