<h2>Today's Events</h2>

<p>Hello{{ !empty($user->name) ? ' ' . $user->name : '' }},</p>

<p>Here are your events for today:</p>

<ul>
    @foreach ($events as $event)
        <li>
            <strong>{{ $event->title }}</strong>
            @if ($event->event_time)
                - {{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}
            @endif

            @if ($event->type)
                ({{ $event->type }})
            @endif

            @if ($event->status)
                - {{ $event->status }}
            @endif
        </li>
    @endforeach
</ul>

<p>From Project NAIT</p>