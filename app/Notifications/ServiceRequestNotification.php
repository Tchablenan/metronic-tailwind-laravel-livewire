<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ServiceRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ServiceRequest $serviceRequest,
        public string $type = 'received' // received, forwarded, converted
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return match ($this->type) {
            'received' => $this->receivedEmail(),
            'forwarded' => $this->forwardedEmail(),
            'converted' => $this->convertedEmail(),
            default => $this->receivedEmail(),
        };
    }

    private function receivedEmail(): MailMessage
    {
        return (new MailMessage)
            ->subject('Confirmation de réception de votre demande de service')
            ->greeting("Bonjour {$this->serviceRequest->first_name},")
            ->line('Nous avons bien reçu votre demande de service.')
            ->line("**Numéro de demande:** {$this->serviceRequest->id}")
            ->line("**Service demandé:** {$this->serviceRequest->service_type}")
            ->line("**Date de création:** {$this->serviceRequest->created_at->format('d/m/Y H:i')}")
            ->line('Nous vous contacterons dans les plus brefs délais.')
            ->line('Merci !');
    }

    private function forwardedEmail(): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouvelle demande de service en attente')
            ->greeting("Bonjour,")
            ->line('Une nouvelle demande de service a été reçue et transmise pour traitement.')
            ->line("**Numéro de demande:** {$this->serviceRequest->id}")
            ->line("**Patient:** {$this->serviceRequest->full_name}")
            ->line("**Email:** {$this->serviceRequest->email}")
            ->line("**Service:** {$this->serviceRequest->service_type}")
            ->action('Voir la demande', url("/service-requests/{$this->serviceRequest->id}"))
            ->line('Merci !');
    }

    private function convertedEmail(): MailMessage
    {
        return (new MailMessage)
            ->subject('Votre demande a été convertie en rendez-vous')
            ->greeting("Bonjour {$this->serviceRequest->first_name},")
            ->line('Votre demande de service a été convertie en rendez-vous.')
            ->line("**Numéro de demande:** {$this->serviceRequest->id}")
            ->line('Consultez votre email pour les détails du rendez-vous.')
            ->action('Voir mon rendez-vous', url("/appointments"))
            ->line('Merci !');
    }
}
