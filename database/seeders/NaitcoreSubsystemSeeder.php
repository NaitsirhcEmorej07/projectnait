<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NaitcoreSubsystemSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('subsystem_tbl')->upsert(
            [
                [
                    'user_id' => 1,
                    'name' => 'NaitNetwork',
                    'code' => 'naitnetwork',
                    'route' => 'naitnetwork.index',
                    'icon' => 'pi pi-users',
                    'description' => 'Network management system',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'user_id' => 1,
                    'name' => 'NaitNote',
                    'code' => 'naitnote',
                    'route' => 'naitnote.index',
                    'icon' => 'pi pi-book',
                    'description' => 'Note management system',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'user_id' => 1,
                    'name' => 'NaitCalendar',
                    'code' => 'naitcalendar',
                    'route' => 'naitcalendar.index',
                    'icon' => 'pi pi-calendar',
                    'description' => 'Calendar tasking and event tools',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'user_id' => 1,
                    'name' => 'NaitPolling',
                    'code' => 'naitpolling',
                    'route' => 'naitpolling.index',
                    'icon' => 'pi pi-comment',
                    'description' => 'Chat Users using Ajax Polling',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ],
            ['user_id', 'code'],
            ['name', 'route', 'icon', 'description', 'is_active', 'updated_at']
        );
    }
}