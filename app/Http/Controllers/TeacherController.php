<?php

namespace App\Http\Controllers;

use App\Models\PointHistory;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function me(Request $request): JsonResponse
    {
        $user = $request->user() ?? auth()->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function history(Request $request): JsonResponse
    {
        $user = $request->user() ?? auth()->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $perPage = (int) $request->query('size', 20);

        $history = PointHistory::with(['student.schoolClass', 'rule'])
            ->where('teacher_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate($perPage);

        $items = $history->getCollection()->map(function (PointHistory $record) {
            /** @var Student $student */
            $student = $record->student;

            return [
                'id' => $record->id,
                'student_name' => $student?->user?->name,
                'student_class' => $student?->schoolClass?->name ?? 'Без класса',
                'rule_description' => $record->rule?->description,
                'points_changed' => $record->points,
                'comment' => $record->comment,
                'created_at' => $record->created_at,
            ];
        });

        $history->setCollection($items);

        return response()->json($history);
    }

    public function showHistory(int $id, Request $request): JsonResponse
    {
        $user = $request->user() ?? auth()->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $record = PointHistory::with(['student.schoolClass', 'rule'])
            ->where('id', $id)
            ->first();

        if (! $record) {
            return response()->json(['message' => 'Assignment not found'], 404);
        }

        if ($record->teacher_id !== $user->id) {
            return response()->json(['message' => 'You can only view your own assignments'], 403);
        }

        $student = $record->student;

        return response()->json([
            'id' => $record->id,
            'student_name' => $student?->user?->name,
            'student_class' => $student?->schoolClass?->name ?? 'Без класса',
            'rule_description' => $record->rule?->description,
            'points_changed' => $record->points,
            'comment' => $record->comment,
            'created_at' => $record->created_at,
            'can_delete' => true,
        ]);
    }

    public function deleteHistory(int $id, Request $request): JsonResponse
    {
        $user = $request->user() ?? auth()->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $record = PointHistory::with('student')
            ->where('id', $id)
            ->first();

        if (! $record) {
            return response()->json(['message' => 'Assignment not found'], 404);
        }

        if ($record->teacher_id !== $user->id) {
            return response()->json(['message' => 'You can only delete your own assignments'], 403);
        }

        $student = $record->student;

        if ($student) {
            $student->current_points -= $record->points;
            $student->save();
        }

        $record->delete();

        return response()->json(null, 204);
    }
}
