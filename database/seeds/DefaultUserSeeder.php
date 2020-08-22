<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            'id'        => 1,
            'name'      => 'Brad Davidson',
            'email'     => 'brad.davidson@gmail.com',
            'password'  => Hash::make('brad@adminShiv#$')
        ]);
    }
}
