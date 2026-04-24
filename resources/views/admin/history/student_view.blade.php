@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-semibold mb-2">История по ученику: {{ $student->user?->name ?? '-' }}</h1>
    <p class="mb-4 text-sm text-gray-600">
        Класс: {{ $student->schoolClass?->name ?? 'Без класса' }} |
        Текущие баллы: {{ $student->current_points }}
    </p>

    <a href="{{ route('admin.students.index') }}" class="text-sm text-blue-600 hover:underline mb-4 inline-block">Назад к ученикам</a>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2 px-3">Когда</th>
                    <th class="text-left py-2 px-3">Учитель</th>
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
                        <td class="py-2 px-3">{{ $record->teacher?->name ?? 'Админ' }}</td>
                        <td class="py-2 px-3">{{ $record->rule?->description ?? $record->rule?->name }}</td>
                        <td class="py-2 px-3">
                            @if ($record->points > 0)
                                <span class="text-green-600">+{{ $record->points }}</span>
                            @else
                                <span class="text-red-600">{{ $record->points }}</span>
                            @endif
                        </td>
                        <td class="py-2 px-3">{{ $record->comment }}</td>
                        <td class="py-2 px-3">
                            <form method="POST" action="{{ route('admin.points.cancel', $record) }}" onsubmit="return confirm('Удалить запись истории и откатить баллы?');">
                                @csrf
                                <button type="submit" class="text-red-600 text-sm">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-3 px-3 text-center text-gray-500">История пока пуста.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
