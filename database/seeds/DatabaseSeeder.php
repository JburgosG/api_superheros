<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PublisherSeeder::class);
        $this->call(HeroSeeder::class);
        $this->call(UserSeeder::class);
    }
}
