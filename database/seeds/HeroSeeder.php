<?php

use App\Hero;
use App\Publisher;
use Illuminate\Database\Seeder;

class HeroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = $this->serviceHeroes();
        $publishers = collect($data)->map(function($hero){
            $model = new Hero();
            $publisher = Publisher::where('name', $hero->publisher)->first();
            
            $model->fill([
                'name' => $hero->name,
                'picture' => $hero->picture,
                'description' => $hero->info,
                'publisher_id' => $publisher->id
            ]);

            return $model->save();
        });
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
