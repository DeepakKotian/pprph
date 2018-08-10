<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'first_name' => 'testme1',
            'email' => 'testme1@me.com',
            'photo'=>'userdefault.jpg',
            'password' => bcrypt('123456'),
        ]);
    }
}
