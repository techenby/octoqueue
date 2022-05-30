<?php

namespace Database\Seeders;

use App\Models\Printer;
use App\Models\PrintJobType;
use App\Models\Spool;
use App\Models\User;
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
        $user = User::factory()->withPersonalTeam('Newest Newhouse')->create(['name' => 'Andy Newhouse', 'email' => 'hi@andymnewhouse.me']);
        $team = $user->currentTeam;

        PrintJobType::factory()->for($team)->create(['name' => 'Fun']);

        Printer::factory()
            ->for($team)
            ->for(Spool::factory()->for($team))
            ->create([
                'name' => 'Eevee',
                'model' => 'Ender 3',
                'url' => 'http://eevee.local',
                'api_key' => 'F51A67E655A84E07B7FF62506AFE4DE0',
            ]);
    }
}
