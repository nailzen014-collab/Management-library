<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            $this->command?->warn('Demo user seeding is only enabled in local and testing environments.');

            return;
        }

        User::query()->updateOrCreate(
            ['email' => 'api.tester@example.com'],
            [
                'name' => 'API Tester',
                'email_verified_at' => now(),
                'password' => 'password',
            ],
        );
    }
}
