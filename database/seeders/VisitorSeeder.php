<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VisitorSeeder extends Seeder
{
    public function run(): void
    {
        $visitors = [];

        // Generar 50 visitantes con IPs aleatorias y fechas aleatorias en los últimos 30 días
        for ($i = 0; $i < 2000; $i++) {
            $visitors[] = [
                'ip_address' => $this->generateRandomIp(),
                'created_at' => now()->subDays(rand(0, 365))->subMinutes(rand(0, 1440)),
                'updated_at' => now(),
            ];
        }

        DB::table('visitors')->insert($visitors);
    }

    private function generateRandomIp(): string
    {
        return rand(1, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(1, 255);
    }
}
