<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NaitCalendarEvent;
use App\Mail\TodayEventsMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendTodayEvents extends Command
{
    protected $signature = 'send:today-events';
    protected $description = 'Send today events email to each user';

    public function handle()
    {
        $today = Carbon::today()->toDateString();

        $eventsByUser = NaitCalendarEvent::with('user')
            ->where('event_date', $today)
            ->orderBy('event_time')
            ->get()
            ->groupBy('user_id');

        if ($eventsByUser->isEmpty()) {
            $this->info('No events found for today.');
            return Command::SUCCESS;
        }

        foreach ($eventsByUser as $userId => $events) {
            $user = optional($events->first())->user;

            if (!$user || empty($user->email)) {
                $this->warn("Skipped user_id {$userId}: no user found or no email.");
                continue;
            }

            Mail::to($user->email)->send(new TodayEventsMail($user, $events));

            $this->info("Email sent to {$user->email}");
        }

        return Command::SUCCESS;
    }
}