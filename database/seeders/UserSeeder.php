<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Rogger Pucuji',
            'user' => 'Rogger',
            'email' => 'roggerp2016@hotmail.com',
            'password' => Hash::make('Rogger021999#'),
            'active' => '1',
            "created_at" =>  \Carbon\Carbon::now(),
            "updated_at" => \Carbon\Carbon::now(),
        ]);
        
         // Segundo registro
         DB::table('users')->insert([
            'name' => 'Carlos Angulo',
            'user' => 'Carlos',
            'email' => 'cangulo009@outlook.es',
            'password' => Hash::make('carlosangulo1234'),
            'active' => '1',
            "created_at" =>  \Carbon\Carbon::now(),
            "updated_at" => \Carbon\Carbon::now(),
        ]);
        
    }
}
