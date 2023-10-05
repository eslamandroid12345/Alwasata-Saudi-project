<?php

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
        // $this->call(UsersTableSeeder::class);
        // $this->call(DeleteAgentReceivedRequestsSeeder::class);
        $this->call(UndoFreezeSeeder::class);
        $this->call(AppDetailsSeeder::class);
        $this->call(UniversitySeeder::class);
    }
}
