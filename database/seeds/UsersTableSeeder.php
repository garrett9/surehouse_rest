<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();

       	$users = [
       			[
       					'name' => 'admin',
       					'email' => 'test@test.com',
       					'permission' => 'super admin',
       					'password' => Hash::make('solard2015'),
       					'active' => true
       			],
       			[
       					'name' => 'admin_api',
       					'email' => 'test@test.com1',
       					'permission' => 'admin',
       					'password' => Hash::make('admin_api_password_12'),
       					'active' => true
       			]
       	];
       	
       	foreach($users as $user)
       		DB::table('users')->insert($user);
    }

}