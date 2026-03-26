<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\NaitCalendarEvent; // adjust if your model name differs

class ImportHolidays extends Command
{
    protected $signature = 'holidays:import {year=2026}';
    protected $description = 'Import holidays into NaitCalendar';

    public function handle()
    {
        $year = $this->argument('year');

        $this->info("Fetching holidays for {$year}...");

        $response = Http::get("https://date.nager.at/api/v3/PublicHolidays/{$year}/PH");

        if (!$response->successful()) {
            $this->error('Failed to fetch holidays.');
            return;
        }

        $holidays = $response->json();

        foreach ($holidays as $holiday) {
            NaitCalendarEvent::updateOrCreate(
                [
                    'event_date' => $holiday['date'],
                    'title' => $holiday['localName'],
                ],
                [
                    'description' => $holiday['name'],
                    'type' => 'HOLIDAY',
                    'status' => 'PENDING',
                    'user_id' => 1 // ⚠️ change later if multi-user
                ]
            );
        }

        $this->info('Holidays imported successfully!');
    }
}