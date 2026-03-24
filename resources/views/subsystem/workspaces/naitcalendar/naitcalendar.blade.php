@php
    use Carbon\Carbon;

    $currentDate = $currentDate ?? now()->startOfMonth();
    $month = $month ?? now()->month;
    $year = $year ?? now()->year;
    $startDayOfWeek = $startDayOfWeek ?? $currentDate->copy()->startOfMonth()->dayOfWeek;
    $daysInMonth = $daysInMonth ?? $currentDate->copy()->endOfMonth()->day;
    $events = $events ?? collect();

    $eventsJson = $events->map(function ($dayEvents) {
        return $dayEvents
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'event_date' => $event->event_date,
                    'event_time' => $event->event_time,
                    'type' => $event->type,
                    'status' => $event->status,
                ];
            })
            ->values();
    });

    $todayDate = now()->toDateString();
    $todayLabel = now()->format('F d, Y');
    $todayEvents = $events[$todayDate] ?? collect();

@endphp


<div x-data="{
    openDayModal: false,
    selectedDate: '',
    selectedDateLabel: '',
    selectedEvents: [],
    allEvents: {{ Js::from($eventsJson) }},
    todayDate: '{{ $todayDate }}',
    todayLabel: '{{ $todayLabel }}',
    hasTodayEvents: {{ $todayEvents->count() ? 'true' : 'false' }},

    openModal(date, label) {
        this.selectedDate = date;
        this.selectedDateLabel = label;
        this.selectedEvents = this.allEvents[date] ?? [];
        this.openDayModal = true;
    },

    init() {
        const modalKey = `naitcalendar-today-modal-${this.todayDate}`;

        if (this.hasTodayEvents && !sessionStorage.getItem(modalKey)) {
            setTimeout(() => {
                this.openModal(this.todayDate, this.todayLabel);
                sessionStorage.setItem(modalKey, 'shown');
            }, 300);
        }
    }
}" class="max-w-7xl mx-auto ">

    <div class="bg-white shadow-sm rounded-2xl p-5 sm:p-6">

        {{-- HEADER --}}
        <div class="mb-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Nait Calendar</h1>
                    <p class="text-sm text-gray-500">Click a date to manage your daily events</p>
                </div>

                {{-- DESKTOP NAV --}}
                <div class="hidden sm:flex items-center gap-2">
                    <a href="{{ route('subsystem.landing', ['code' => 'naitcalendar', 'month' => $currentDate->copy()->subMonth()->month, 'year' => $currentDate->copy()->subMonth()->year]) }}"
                        class="px-3 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-sm">
                        Prev
                    </a>

                    <div class="px-4 py-2 rounded-xl bg-indigo-50 text-indigo-700 text-sm font-medium">
                        {{ $currentDate->format('F Y') }}
                    </div>

                    <a href="{{ route('subsystem.landing', ['code' => 'naitcalendar', 'month' => $currentDate->copy()->addMonth()->month, 'year' => $currentDate->copy()->addMonth()->year]) }}"
                        class="px-3 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-sm">
                        Next
                    </a>
                </div>
            </div>
        </div>

        {{-- SUCCESS --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
                class="mb-4 rounded-xl bg-green-100 text-green-700 p-3 text-sm">

                {{ session('success') }}
            </div>
        @endif

        {{-- WEEK DAYS --}}
        <div class="grid grid-cols-7 gap-2 mb-2">
            @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dayName)
                <div class="text-center text-xs sm:text-sm font-semibold text-gray-500 py-2">
                    {{ $dayName }}
                </div>
            @endforeach
        </div>

        {{-- CALENDAR GRID --}}
        <div class="grid grid-cols-7 gap-1.5 sm:gap-2">
            {{-- EMPTY BOXES --}}
            @for ($i = 0; $i < $startDayOfWeek; $i++)
                <div class="min-h-[64px] sm:min-h-[100px] rounded-xl sm:rounded-2xl bg-gray-50 border border-gray-100">
                </div>
            @endfor

            {{-- DAYS --}}
            @for ($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $fullDate = Carbon::createFromDate($year, $month, $day)->toDateString();
                    $dayEvents = $events[$fullDate] ?? collect();
                    $isToday = $fullDate === now()->toDateString();
                    $labelDate = Carbon::createFromDate($year, $month, $day)->format('F d, Y');
                @endphp

                <button type="button" @click="openModal('{{ $fullDate }}', '{{ $labelDate }}')"
                    class="min-h-[64px] sm:min-h-[120px] rounded-xl sm:rounded-2xl border p-1.5 sm:p-3 text-left transition hover:shadow-sm
                    {{ $isToday
                        ? 'border-indigo-500 bg-indigo-100 hover:border-indigo-500'
                        : ($dayEvents->count()
                            ? 'border-green-500 bg-green-50 hover:border-green-600'
                            : 'border-gray-200 bg-white hover:border-indigo-300') }}">

                    <div class="flex items-start justify-between">
                        <span
                            class="text-xs sm:text-sm font-bold leading-none {{ $isToday ? 'text-indigo-700' : 'text-gray-800' }}">
                            {{ $day }}
                        </span>

                        @if ($dayEvents->count())
                            <span
                                class="hidden sm:flex min-w-[22px] h-[22px] px-1 rounded-full bg-green-600 text-white text-[11px] items-center justify-center font-medium">
                                {{ $dayEvents->count() }}
                            </span>
                        @endif
                    </div>

                    {{-- DESKTOP ONLY --}}
                    <div class="hidden sm:block mt-2 space-y-1">
                        @foreach ($dayEvents->take(2) as $event)
                            <div class="rounded-lg bg-gray-100 px-2 py-1 text-[11px] text-gray-700 truncate">
                                {{ $event->title }}
                            </div>
                        @endforeach

                        @if ($dayEvents->count() > 2)
                            <div class="text-[11px] text-gray-400">
                                +{{ $dayEvents->count() - 2 }} more
                            </div>
                        @endif
                    </div>
                </button>
            @endfor
        </div>





        {{-- MOBILE NAV (MINIMAL) --}}
        <div class="mt-5 flex sm:hidden justify-center">
            <div class="flex items-center gap-3">

                {{-- PREV --}}
                <a href="{{ route('subsystem.landing', ['code' => 'naitcalendar', 'month' => $currentDate->copy()->subMonth()->month, 'year' => $currentDate->copy()->subMonth()->year]) }}"
                    class="p-1.5 text-gray-500 hover:text-indigo-600 transition">
                    <i class="pi pi-chevron-left text-sm"></i>
                </a>

                {{-- MONTH --}}
                <div class="px-3 py-1.5 text-sm font-medium text-gray-700">
                    {{ $currentDate->format('M Y') }}
                </div>

                {{-- NEXT --}}
                <a href="{{ route('subsystem.landing', ['code' => 'naitcalendar', 'month' => $currentDate->copy()->addMonth()->month, 'year' => $currentDate->copy()->addMonth()->year]) }}"
                    class="p-1.5 text-gray-500 hover:text-indigo-600 transition">
                    <i class="pi pi-chevron-right text-sm"></i>
                </a>

            </div>
        </div>

    </div>

    {{-- DAY MODAL --}}
    <template x-teleport="body">
        <div x-show="openDayModal" x-transition
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4" style="display: none;">

            <div @click.outside="openDayModal = false"
                class="w-full max-w-2xl bg-white rounded-2xl shadow-xl p-5 sm:p-6 max-h-[90vh] overflow-y-auto">

                {{-- MODAL HEADER --}}
                <div class="flex items-start justify-between gap-3 mb-5">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Day Events</h2>
                        <p class="text-sm text-gray-500" x-text="selectedDateLabel"></p>
                    </div>

                    <button type="button" @click="openDayModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="pi pi-times text-lg"></i>
                    </button>
                </div>

                {{-- ADD EVENT FORM ACCORDION --}}
                <div x-data="{ openAddEvent: false }" class="border border-gray-200 rounded-2xl mb-5 overflow-hidden bg-white">
                    <button type="button" @click="openAddEvent = !openAddEvent"
                        class="w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition text-left">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">Add Event</h3>
                            <p class="text-xs text-gray-500">Create a new event for this date</p>
                        </div>

                        <i class="pi text-sm text-gray-400"
                            :class="openAddEvent ? 'pi-chevron-up' : 'pi-chevron-down'"></i>
                    </button>

                    <div x-show="openAddEvent" x-transition class="border-t border-gray-100 p-3">
                        <form action="{{ route('naitcalendar.store') }}" method="POST" class="space-y-4">
                            @csrf

                            <input type="hidden" name="event_date" :value="selectedDate">

                            {{-- TITLE --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Title</label>
                                <input type="text" name="title"
                                    class="w-full rounded-xl border-gray-300 text-sm placeholder:text-gray-400 focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Enter event title" required>
                            </div>

                            {{-- TIME + TYPE --}}
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Time</label>
                                    <input type="time" name="event_time" value="00:00"
                                        class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Type</label>
                                    <select name="type"
                                        class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="" disabled selected>Select type</option>
                                        <option value="PERSONAL">Personal</option>
                                        <option value="RELATIONAL">Relational</option>
                                        <option value="WORK">Work</option>
                                    </select>
                                </div>
                            </div>

                            {{-- STATUS --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                                <select name="status"
                                    class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="" disabled selected>Select status</option>
                                    <option value="PENDING">Pending</option>
                                    <option value="DONE">Done</option>
                                </select>
                            </div>

                            {{-- DESCRIPTION --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Description</label>
                                <textarea name="description" rows="9"
                                    class="w-full rounded-xl border-gray-300 text-sm placeholder:text-gray-400 focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Add notes or details"></textarea>
                            </div>

                            {{-- BUTTON --}}
                            <div class="pt-1">
                                <button type="submit"
                                    class="w-full rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 transition">
                                    Save Event
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- EVENT LIST --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Events for this day</h3>

                    <div class="space-y-3" x-show="selectedEvents.length > 0">
                        <template x-for="event in selectedEvents" :key="event.id">
                            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <h4 class="font-semibold text-gray-900" x-text="event.title"></h4>

                                        <template x-if="event.event_time">
                                            <p class="text-sm text-indigo-600 mt-1" x-text="event.event_time"></p>
                                        </template>

                                        <template x-if="event.description">
                                            <p class="text-sm text-gray-600 mt-1 whitespace-pre-line"
                                                x-text="event.description">
                                            </p>
                                        </template>

                                        <div class="flex flex-wrap gap-2 mt-2">
                                            <template x-if="event.type">
                                                <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-700"
                                                    x-text="event.type"></span>
                                            </template>

                                            <template x-if="event.status">
                                                <span
                                                    class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700"
                                                    x-text="event.status"></span>
                                            </template>
                                        </div>
                                    </div>

                                    <form :action="`/naitcalendar/${event.id}/delete`" method="POST"
                                        onsubmit="return confirm('Delete this event?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="p-2 rounded-full text-gray-400 hover:text-red-600 hover:bg-red-50 transition">
                                            <i class="pi pi-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="selectedEvents.length === 0"
                        class="rounded-2xl border border-dashed border-gray-300 p-5 text-sm text-gray-500 text-center">
                        No events for this day yet.
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
