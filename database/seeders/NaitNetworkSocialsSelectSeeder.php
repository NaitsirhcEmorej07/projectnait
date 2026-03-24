<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NaitNetworkSocialsSelectSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('naitnetwork_socials_select_tbl')->upsert([
            [
                
                'name' => 'Facebook',
                'code' => 'facebook',
                'icon' => 'pi pi-facebook',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Twitter',
                'code' => 'twitter',
                'icon' => 'pi pi-twitter',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Instagram',
                'code' => 'instagram',
                'icon' => 'pi pi-instagram',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tiktok',
                'code' => 'tiktok',
                'icon' => 'pi pi-tiktok',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'LinkedIn',
                'code' => 'linkedin',
                'icon' => 'pi pi-linkedin',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'GitHub',
                'code' => 'github',
                'icon' => 'pi pi-github',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Website',
                'code' => 'website',
                'icon' => 'pi pi-globe',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ], ['code'], ['name', 'icon', 'is_active', 'updated_at']);
    }
}