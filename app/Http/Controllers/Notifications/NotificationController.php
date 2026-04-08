<?php

namespace App\Http\Controllers\Notifications;

use App\Facades\AppNotification;
use App\Models\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController
{
    /**
     * Display a listing of the user's notifications.
     */
    public function index()
    {
        $user = Auth::user();

        // Safety fallback if testing without login 
        // For production, auth middleware solves this
        if (!$user) {
            $user = \App\Models\Utilisateurs\User::first();
        }

        $notifications = $user ? AppNotification::getNotifications($user) : collect();

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(Request $request, Notification $notification)
    {
        $user = Auth::user() ?? \App\Models\Utilisateurs\User::first();
        
        if ($notification->id_utilisateur === $user->id_utilisateur) {
            AppNotification::marquerCommeLue($notification);
        }

        return redirect()->back();
    }
}
