@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-semibold mb-2">История по учителю: {{ $user->name }}</h1>
    <p class="mb-4 text-sm text-gray-600">{{ $user->email }}</p>

    <a href="{{ route('admin.teachers.index') }}" class="text-sm text-blue-600 hover:underline mb-4 inline-block">Назад к учителям</a>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2 px-3">Когда</th>
                    <th class="text-left py-2 px-3">Ученик</th>
                    <th class="text-left py-2 px-3">Класс</th>
                    <th class="text-left py-2 px-3">Правило</th>
                    <th class="text-left py-2 px-3">Баллы</th>
                    <th class="text-left py-2 px-3">Комментарий</th>
                    <th class="text-left py-2 px-3">Действие</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($history as $record)
                    <tr class="border-b last:border-0">
                        <td class="py-2 px-3 whitespace-nowrap">{{ $record->created_at->format('d.m.Y H:i') }}</td>
                        <td class="py-2 px-3">{{ $record->student?->user?->name ?? '—' }}</td>
                        <td class="py-2 px-3">{{ $record->student?->schoolClass?->name ?? '—' }}</td>
                        <td class="py-2 px-3">{{ $record->rule?->name ?? '—' }}</td>
                        <td class="py-2 px-3">
                            @if ($record->points > 0)
                                <span class="text-green-600">+{{ $record->points }}</span>
                            @else
                                <span class="text-red-600">{{ $record->points }}</span>
                            @endif
                        </td>
                        <td class="py-2 px-3">{{ $record->comment ?? '—' }}</td>
                        <td class="py-2 px-3">
                            <form method="POST" action="{{ route('admin.points.cancel', $record) }}" onsubmit="return confirm('Удалить запись истории и откатить баллы?');">
                                @csrf
                                <button type="submit" class="text-red-600 text-sm">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-3 px-3 text-center text-gray-500">История пуста.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
