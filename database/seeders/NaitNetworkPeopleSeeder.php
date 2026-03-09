<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NaitNetworkPeopleSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->value('id');

        if (!$userId) {
            $this->command->warn('No user found in users table. Please create a user first.');
            return;
        }

        $people = [
            [
                'name' => 'Juan Dela Cruz',
                'email' => 'juan@example.com',
                'phone' => '09171234567',
                'summary' => 'Laravel developer focused on backend systems and APIs.',
                'notes' => 'Met through tech meetup. Good for web app projects.',
                'is_public' => true,
                'public_expires_at' => Carbon::now()->addDays(30),
                'show_email_publicly' => true,
                'show_phone_publicly' => false,
                'roles' => ['Programmer', 'AI Engineer'],
                'socials' => [
                    [
                        'platform' => 'github',
                        'url' => 'https://github.com/juandelacruz',
                        'is_public' => true,
                    ],
                    [
                        'platform' => 'linkedin',
                        'url' => 'https://linkedin.com/in/juandelacruz',
                        'is_public' => true,
                    ],
                ],
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria@example.com',
                'phone' => '09179876543',
                'summary' => 'Creative designer for branding, UI, and digital content.',
                'notes' => 'Strong in clean modern design.',
                'is_public' => true,
                'public_expires_at' => Carbon::now()->addDays(15),
                'show_email_publicly' => false,
                'show_phone_publicly' => false,
                'roles' => ['Designer', 'Marketing'],
                'socials' => [
                    [
                        'platform' => 'behance',
                        'url' => 'https://behance.net/mariasantos',
                        'is_public' => true,
                    ],
                    [
                        'platform' => 'instagram',
                        'url' => 'https://instagram.com/mariasantos.design',
                        'is_public' => true,
                    ],
                ],
            ],
            [
                'name' => 'Carlos Reyes',
                'email' => 'carlos@example.com',
                'phone' => '09175556666',
                'summary' => 'Crypto trader and startup founder active in Web3 communities.',
                'notes' => 'Potential partner for crypto and startup ideas.',
                'is_public' => false,
                'public_expires_at' => null,
                'show_email_publicly' => false,
                'show_phone_publicly' => false,
                'roles' => ['Crypto Trader', 'Founder', 'Investor'],
                'socials' => [
                    [
                        'platform' => 'x',
                        'url' => 'https://x.com/carlosreyes',
                        'is_public' => true,
                    ],
                    [
                        'platform' => 'telegram',
                        'url' => 'https://t.me/carlosreyes',
                        'is_public' => false,
                    ],
                ],
            ],
        ];

        foreach ($people as $personData) {
            $personId = DB::table('naitnetwork_people_tbl')->insertGetId([
                'user_id' => $userId,
                'profile_picture' => null,
                'name' => $personData['name'],
                'slug' => Str::slug($personData['name']) . '-' . Str::lower(Str::random(5)),
                'email' => $personData['email'],
                'phone' => $personData['phone'],
                'summary' => $personData['summary'],
                'notes' => $personData['notes'],
                'is_public' => $personData['is_public'],
                'public_token' => Str::random(40),
                'public_expires_at' => $personData['public_expires_at'],
                'show_email_publicly' => $personData['show_email_publicly'],
                'show_phone_publicly' => $personData['show_phone_publicly'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($personData['roles'] as $roleName) {
                $role = DB::table('naitnetwork_roles_tbl')
                    ->where('name', $roleName)
                    ->first();

                if ($role) {
                    DB::table('naitnetwork_people_roles_tbl')->insert([
                        'person_id' => $personId,
                        'role_id' => $role->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            foreach ($personData['socials'] as $social) {
                DB::table('naitnetwork_people_socials_tbl')->insert([
                    'person_id' => $personId,
                    'platform' => $social['platform'],
                    'url' => $social['url'],
                    'is_public' => $social['is_public'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}