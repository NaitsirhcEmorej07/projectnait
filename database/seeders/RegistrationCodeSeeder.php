<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RegistrationCode;
use Illuminate\Support\Str;

class RegistrationCodeSeeder extends Seeder
{
    public function run(): void
    {
        // how many codes you want
        $totalCodes = 20;

        for ($i = 0; $i < $totalCodes; $i++) {

            RegistrationCode::create([
                'code' => 'NAIT-' . strtoupper(Str::random(6)),
                'is_used' => false,
            ]);

        }
    }
}