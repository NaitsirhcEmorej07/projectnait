<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NaitcoreSubsystemSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (! $user) {
            $this->command->warn('No user found. Please create a user first.');
            return;
        }

        DB::table('subsystem_tbl')->upsert(
            [
                [
                    'user_id' => $user->id,
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
                    'user_id' => $user->id,
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
                    'user_id' => $user->id,
                    'name' => 'NaitCalendar',
                    'code' => 'naitcalendar',
                    'route' => 'naitcalendar.index',
                    'icon' => 'pi pi-calendar',
                    'description' => 'Calendar tasking and event tools',
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