<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $user = \App\Models\User::factory()->withPersonalTeam()->create([
            'name' => 'Andy Newhouse',
            'email' => 'hi@andymnewhouse.me',
        ]);

        \App\Models\Printer::factory()
            ->for($user->currentTeam)
            ->count(3)
            ->state(new Sequence(
                ['name' => 'Bulbasaur'],
                ['name' => 'Charmander'],
                ['name' => 'Squirtle'],
            ))
            ->create();
    }
}
