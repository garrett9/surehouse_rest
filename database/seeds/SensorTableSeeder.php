<?php
use Illuminate\Database\Seeder;
class SensorTableSeeder extends Seeder {
	public function run() {
		DB::table ( 'sensors' )->delete ();
		
		$today = date ( 'Y-m-d H:i:s' );
		
		$gateway = 5;
		
		$sensors = [ 
				[ 
						'name' => 'CT_SERVICE_L1',
						'display_name' => 'Service Supply (L1)',
						'units' => 'Units',
						'gateway' => $gateway,
						'created_at' => $today,
						'updated_at' => $today 
				],
				[ 
						'name' => 'CT_SERVICE_L2',
						'display_name' => 'Service Supply (L2)',
						'units' => 'Units',
						'gateway' => $gateway,
						'created_at' => $today,
						'updated_at' => $today 
				],
				[ 
						'name' => 'CT_PV1',
						'display_name' => 'Inverter 1 Production',
						'units' => 'Units',
						'gateway' => $gateway,
						'created_at' => $today,
						'updated_at' => $today 
				],
				[ 
						'name' => 'CT_PV2',
						'display_name' => 'Inverter 2 Production',
						'units' => 'Units',
						'gateway' => $gateway,
						'created_at' => $today,
						'updated_at' => $today 
				],
				[ 
						'name' => 'CT_DHW_L1',
						'display_name' => 'Electric Hot Water Load (L1)',
						'units' => 'Units',
						'gateway' => $gateway,
						'created_at' => $today,
						'updated_at' => $today
				],
				[ 
						'name' => 'CT_DHW_L2',
						'display_name' => 'Electric Hot Water Load (L2)',
						'units' => 'Units',
						'gateway' => $gateway,
						'created_at' => $today,
						'updated_at' => $today 
				],
				[ 
						'name' => 'CT_EV_L1',
						'display_name' => 'EV Charger Load (L2)',
						'units' => 'Units',
						'gateway' => $gateway,
						'created_at' => $today,
						'updated_at' => $today 
				],
				[ 
						'name' => 'CT_EV_L2',
						'display_name' => 'EV Charger Load (L2)',
						'units' => 'Units',
						'gateway' => $gateway,
						'created_at' => $today,
						'updated_at' => $today 
				],
				[ 
						'name' => 'CT_AHU_L1',
						'display_name' => 'Air Handler Unit Load (L1)',
						'units' => 'Units',
						'gateway' => $gateway,
						'created_at' => $today,
						'updated_at' => $today 
				],
				[ 
						'name' => 'CT_AHU_L2',
						'display_name' => 'Air Handler Unit Load (L2)',
						'units' => 'Units',
						'gateway' => $gateway,
						'created_at' => $today,
						'updated_at' => $today 
				],
				[ 
						'name' => 'CT_ACCU_L1',
						'display_name' => 'Accumulator Load (L1)',
						'units' => 'Units',
						'gateway' => $gateway,
						'created_at' => $today,
						'updated_at' => $today 
				],
				[ 
						'name' => 'CT_ACCU_L2',
						'display_name' => 'Accumulator Load (L2)',
						'units' => 'Units',
						'gateway' => $gateway,
						'created_at' => $today,
						'updated_at' => $today 
				],
				[ 
						'name' => 'CT_ERV',
						'display_name' => 'ERV Load',
						'units' => 'Units',
						'gateway' => $gateway,
						'created_at' => $today,
						'updated_at' => $today 
				],
				[ 
						'name' => 'CT_PUMP1',
						'display_name' => 'Supply Pump Load',
						'units' => 'Units',
						'gateway' => $gateway,
						'created_at' => $today,
						'updated_at' => $today 
				],
				[ 
						'name' => 'CT_PUMP2',
						'display_name' => 'Sump Pump Load',
						'units' => 'Units',
						'gateway' => $gateway,
						'created_at' => $today,
						'updated_at' => $today 
				],
				[ 
						'name' => 'CT_CLOTHES_W',
						'display_name' => 'Clothes Washer Load',
						'units' => 'Units',
						'gateway' => $gateway,
						'created_at' => $today,
						'updated_at' => $today 
				],
				[ 
						'name' => 'CT_CLOTHES_D',
						'display_name' => 'Clothes Dryer Load',
						'units' => 'Units',
						'gateway' => $gateway,
						'created_at' => $today,
						'updated_at' => $today 
				],
				[ 
						'name' => 'CT_COOKING_L1',
						'display_name' => 'Electric Stove/Oven Load (L1)',
						'units' => 'Units',
						'gateway' => $gateway,
						'created_at' => $today,
						'updated_at' => $today 
				],
				[ 
						'name' => 'CT_COOKING_L2',
						'display_name' => 'Electric Stove/Oven Load (L2)',
						'units' => 'Units',
						'gateway' => $gateway,
						'created_at' => $today,
						'updated_at' => $today 
				],
				[ 
						'name' => 'CT_FRIDGE',
						'display_name' => 'Refridgerator Load',
						'units' => 'Units',
						'gateway' => $gateway,
						'created_at' => $today,
						'updated_at' => $today 
				],
				[ 
						'name' => 'CT_DISH',
						'display_name' => 'Dishwasher Load',
						'units' => 'Units',
						'gateway' => $gateway,
						'created_at' => $today,
						'updated_at' => $today 
				],
				[ 
						'name' => 'CT_LIGHTS_L1',
						'display_name' => 'Lighting Load (L1)',
						'units' => 'Units',
						'gateway' => $gateway,
						'created_at' => $today,
						'updated_at' => $today 
				],
				[ 
						'name' => 'CT_LIGHTS_L2',
						'display_name' => 'Lighting Load (L2)',
						'units' => 'Units',
						'gateway' => $gateway,
						'created_at' => $today,
						'updated_at' => $today 
				],
				[ 
						'name' => 'CT_MEDIA',
						'display_name' => 'Entertainment Center Load',
						'units' => 'Units',
						'gateway' => $gateway,
						'created_at' => $today,
						'updated_at' => $today 
				]
		];
		
		foreach ( $sensors as $sensor )
			DB::table ( 'sensors' )->insert ( $sensor );
	}
}