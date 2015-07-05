<?php

use Illuminate\Database\Seeder;

class GatewaysTableSeeder extends Seeder {

    public function run()
    {
        DB::table('gateways')->delete();

        $today = date('Y-m-d H:i:s');
        
       	$gateways = [
       			[
       					'name' => 'EGUAGE',
       					'IP' => '192.168.1.2',
       					'port' => 8443,
       					'type' => 'eGuage',
       					'created_at' => $today,
       					'updated_at' => $today
       			]
       	];
       	
       	foreach($gateways as $gateway)
       		DB::table('gateways')->insert($gateway);
    }

}