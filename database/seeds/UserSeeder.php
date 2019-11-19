<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();

        $data = [
            'name' => 'Jairo Yesid Burgos Guarín',
            'email' => 'jyburgos40@gmail.com',
            'password' => 123456,
        ];

        $user->fill($data);
        $user->save();
    }
}