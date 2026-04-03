<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user() ?? auth()->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $notifications = Notification::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json($notifications);
    }

    public function markAsRead(int $id, Request $request): JsonResponse
    {
        $user = $request->user() ?? auth()->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $notification = Notification::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        $notification->update(['is_read' => true]);

        return response()->json(['status' => 'ok']);
    }
}
