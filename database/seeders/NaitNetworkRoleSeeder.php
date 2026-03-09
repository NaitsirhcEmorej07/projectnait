<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NaitNetworkRoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Programmer',
            'Database Admin',
            'Crypto Trader',
            'Designer',
            'Lawyer',
            'Founder',
            'Investor',
            'Marketing',
            'Government',
            'AI Engineer',
        ];

        foreach ($roles as $role) {
            DB::table('naitnetwork_roles_tbl')->updateOrInsert(
                ['slug' => Str::slug($role)],
                [
                    'name' => $role,
                    'slug' => Str::slug($role),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}