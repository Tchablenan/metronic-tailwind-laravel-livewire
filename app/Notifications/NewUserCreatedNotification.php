<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class NewUserCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $user,
        public string $temporaryPassword
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Bienvenue ! Votre compte a été créé')
            ->greeting("Bienvenue, {$this->user->first_name}!")
            ->line('Votre compte utilisateur a été créé avec succès.')
            ->line("Email: {$this->user->email}")
            ->line("Mot de passe temporaire: {$this->temporaryPassword}")
            ->line('Veuillez changer votre mot de passe après votre première connexion.')
            ->action('Accéder à votre compte', url('/login'))
            ->line('Merci de votre confiance.');
    }
}
