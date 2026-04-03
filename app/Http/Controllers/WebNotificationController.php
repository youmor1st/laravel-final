<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebNotificationController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user() ?? auth()->user();

        $notifications = Notification::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request, Notification $notification): RedirectResponse
    {
        $user = $request->user() ?? auth()->user();

        if ($notification->user_id !== $user->id) {
            abort(403);
        }

        if (! $notification->is_read) {
            $notification->is_read = true;
            $notification->save();
        }

        return back();
    }
}
