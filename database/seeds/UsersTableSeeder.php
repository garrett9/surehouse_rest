<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();

       	$users = [
       	        [
       	            'name' => 'test_user',
       	            'email' => 'test@test.com',
       	            'permission' => 'admin',
       	            'password' => Hash::make('Testing123'),
       	            'active' => true
       	        ]
       	];
       	
       	foreach($users as $user)
       		DB::table('users')->insert($user);
    }

}