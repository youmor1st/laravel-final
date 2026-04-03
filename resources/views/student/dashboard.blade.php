@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Уведомления</h1>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2 px-3">Когда</th>
                    <th class="text-left py-2 px-3">Заголовок</th>
                    <th class="text-left py-2 px-3">Текст</th>
                    <th class="text-left py-2 px-3">Статус</th>
                    <th class="text-left py-2 px-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($notifications as $notification)
                    <tr class="border-b last:border-0 {{ $notification->is_read ? '' : 'bg-gray-50' }}">
                        <td class="py-2 px-3 whitespace-nowrap">
                            {{ $notification->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="py-2 px-3 font-semibold">
                            {{ $notification->title }}
                        </td>
                        <td class="py-2 px-3">
                            {{ $notification->body }}
                        </td>
                        <td class="py-2 px-3">
                            @if ($notification->is_read)
                                <span class="text-xs text-gray-500">Прочитано</span>
                            @else
                                <span class="text-xs text-indigo-600 font-semibold">Новое</span>
                            @endif
                        </td>
                        <td class="py-2 px-3 text-right">
                            @if (! $notification->is_read)
                                <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                    @csrf
                                    <button type="submit" class="text-xs text-indigo-600 hover:underline">
                                        Отметить как прочитанное
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-3 px-3 text-center text-gray-500">
                            Уведомлений пока нет.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
@endsection
