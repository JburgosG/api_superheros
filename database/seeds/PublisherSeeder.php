<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = $this->serviceHeroes();
        $publishers = collect($data)->pluck('publisher')->unique()->map(function($name){
            return ['name' => $name];
        })->toArray();

        DB::table('publishers')->insert($publishers);
    }

    public function serviceHeroes()
    {
        $url = 'http://35.162.46.100/superheroes/';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $data = curl_exec($curl);
        curl_close($curl);

        return json_decode($data);
    }
}