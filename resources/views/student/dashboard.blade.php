@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Кабинет ученика</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-sm font-medium text-gray-500 mb-1">Имя</h2>
            <div class="text-lg font-semibold">
                {{ $student->user?->name ?? '—' }}
            </div>
            <div class="text-xs text-gray-500 mt-1">
                {{ $student->user?->email }}
            </div>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-sm font-medium text-gray-500 mb-1">Класс</h2>
            <div class="text-lg font-semibold">
                {{ $student->schoolClass?->name ?? 'Без класса' }}
            </div>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-sm font-medium text-gray-500 mb-1">Баллы</h2>
            <div class="text-2xl font-bold">
                {{ $student->current_points }}
            </div>
            <div class="text-xs text-gray-500 mt-1">
                Место в школе: #{{ $globalRank }}
                @if ($classRank)
                    | в классе: #{{ $classRank }}
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold mb-3">История изменений баллов</h2>

            @if ($history->isEmpty())
                <p class="text-sm text-gray-500">История пока пуста.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2 px-3">Когда</th>
                                <th class="text-left py-2 px-3">Кто</th>
                                <th class="text-left py-2 px-3">Правило</th>
                                <th class="text-left py-2 px-3">Баллы</th>
                                <th class="text-left py-2 px-3">Комментарий</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($history as $record)
                                <tr class="border-b last:border-0">
                                    <td class="py-2 px-3 whitespace-nowrap">
                                        {{ $record->created_at->format('d.m H:i') }}
                                    </td>
                                    <td class="py-2 px-3">
                                        {{ $record->teacher?->name ?? 'Админ' }}
                                    </td>
                                    <td class="py-2 px-3">
                                        {{ $record->rule?->description ?? $record->rule?->name }}
                                    </td>
                                    <td class="py-2 px-3">
                                        @if ($record->points > 0)
                                            <span class="text-green-600">+{{ $record->points }}</span>
                                        @else
                                            <span class="text-red-600">{{ $record->points }}</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-3">
                                        {{ $record->comment }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="space-y-4">
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-sm font-semibold mb-2">Топ-10 по школе</h2>
                @if ($topSchool->isEmpty())
                    <p class="text-xs text-gray-500">Пока нет данных.</p>
                @else
                    <ol class="text-sm space-y-1">
                        @foreach ($topSchool as $idx => $s)
                            <li class="flex justify-between">
                                <span>
                                    #{{ $idx + 1 }}
                                    {{ $s->user?->name ?? '—' }}
                                    ({{ $s->schoolClass?->name ?? '—' }})
                                </span>
                                <span class="font-semibold">{{ $s->current_points }}</span>
                            </li>
                        @endforeach
                    </ol>
                @endif
            </div>

            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-sm font-semibold mb-2">Топ-10 в классе</h2>
                @if ($topClass->isEmpty())
                    <p class="text-xs text-gray-500">Пока нет данных.</p>
                @else
                    <ol class="text-sm space-y-1">
                        @foreach ($topClass as $idx => $s)
                            <li class="flex justify-between">
                                <span>
                                    #{{ $idx + 1 }}
                                    {{ $s->user?->name ?? '—' }}
                                </span>
                                <span class="font-semibold">{{ $s->current_points }}</span>
                            </li>
                        @endforeach
                    </ol>
                @endif
            </div>
        </div>
    </div>
@endsection
