<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentConfirmationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Appointment $appointment
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Confirmation de votre rendez-vous')
            ->greeting("Bonjour {$this->appointment->patient->first_name},")
            ->line("Votre rendez-vous a été confirmé.")
            ->line("**Date:** {$this->appointment->appointment_date->format('d/m/Y')}")
            ->line("**Heure:** {$this->appointment->appointment_time->format('H:i')}")
            ->line("**Type:** {$this->appointment->appointment_type_label}")
            ->line("**Lieu:** {$this->appointment->location_label}")
            ->when($this->appointment->doctor, function ($message) {
                $message->line("**Médecin:** {$this->appointment->doctor->full_name}");
            })
            ->action('Voir mon rendez-vous', url("/appointments/{$this->appointment->id}"))
            ->line('Si vous devez annuler, veuillez nous contacter au moins 24h à l\'avance.')
            ->line('Merci !');
    }
}
