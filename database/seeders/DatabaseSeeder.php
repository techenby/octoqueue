<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Tool;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionSeeder::class,
        ]);

        $user = \App\Models\User::factory()->withPersonalTeam()->create([
            'name' => 'Andy Newhouse',
            'email' => 'hi@andymnewhouse.me',
        ]);
        $user->assignRole('Super-Admin');

        \App\Models\Printer::factory()
            ->for($user->currentTeam)
            ->count(3)
            ->state(new Sequence(
                ['name' => 'Bulbasaur'],
                ['name' => 'Charmander'],
                ['name' => 'Squirtle'],
            ))
            ->create();

        \App\Models\Material::factory()
            ->for($user->currentTeam)
            ->count(3)
            ->state(new Sequence(
                ['printer_type' => 'fdm', 'color' => 'Blue', 'color_hex' => '#0000FF'],
                ['printer_type' => 'fdm', 'color' => 'Green', 'color_hex' => '#00FF00'],
                ['printer_type' => 'fdm', 'color' => 'Purple', 'color_hex' => '#5D3FD3'],
            ))
            ->create();

        \App\Models\PrintType::factory()
            ->for($user->currentTeam)
            ->count(3)
            ->state(new Sequence(
                ['name' => 'Fun', 'priority' => '3'],
                ['name' => 'Home', 'priority' => '1'],
                ['name' => 'Gifts', 'priority' => '2'],
            ))
            ->create();
    }
}
