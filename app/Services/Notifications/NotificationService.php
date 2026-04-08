<?php

namespace App\Services\Notifications;

use App\Models\Notifications\Notification;
use App\Models\Utilisateurs\User;
use App\Enums\TypeAlerte;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Retrieve all notifications for a specific user.
     *
     * @param User $destinataire
     * @return Collection
     */
    public function getNotifications(User $destinataire): Collection
    {
        return Notification::where('id_utilisateur', $destinataire->id_utilisateur)
            ->orderBy('date_creation', 'desc')
            ->get();
    }

    /**
     * Create a new notification for a user and trigger the sending process.
     *
     * @param User $destinataire
     * @param string $titre
     * @param string $contenu
     * @param TypeAlerte $type
     * @return Notification
     */
    public function creerNotification(User $destinataire, string $titre, string $contenu, TypeAlerte $type): Notification
    {
        // 1. Create the notification in the database
        $notification = Notification::create([
            'id_utilisateur' => $destinataire->id_utilisateur,
            'titre'          => $titre,
            'contenu'        => $contenu,
            'type_alerte'    => $type,
            'est_lue'        => false,
            'date_creation'  => now(),
        ]);

        // 2. Transmit the notification (via Log simulation for now)
        $this->envoyerNotification($notification);

        return $notification;
    }

    /**
     * Mark a specific notification as read.
     *
     * @param Notification $notification
     * @return void
     */
    public function marquerCommeLue(Notification $notification): void
    {
        $notification->update(['est_lue' => true]);
    }

    /**
     * Transmit the notification to the user (e.g. Email, SMS).
     * Currently simulated using Laravel logs.
     *
     * @param Notification $notification
     * @return void
     */
    public function envoyerNotification(Notification $notification): void
    {
        // Simulate sending an external alert via logs
        Log::info("Notification sent to User ID {$notification->id_utilisateur}", [
            'Type'    => $notification->type_alerte->value,
            'Title'   => $notification->titre,
            'Content' => $notification->contenu,
            'Time'    => $notification->date_creation->toDateTimeString(),
        ]);
    }
}
