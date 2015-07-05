<?php 

return [
		/*
		 |--------------------------------------------------------------------------
		 | Resilient Mode
		 |--------------------------------------------------------------------------
		 |
		 | By default, Resilient mode will be off, and can only be triggered by
		 | alerts if they created. However, you can manually turn on resilient
		 | mode to see the difference in the web application.
		 |
		 */
		'resilient_mode' => env('resilient_mode', false),
		
		/*
		 |--------------------------------------------------------------------------
		 | Resilient Triggers
		 |--------------------------------------------------------------------------
		 |
		 | When alerts are created in the system, they can be specified to trigger
		 | resilient mode when met. By default, they are allowed, but you can
		 | specify here if you want to turn this feature off.
		 |
		 */
		'resilient_triggers' => env('resilient_triggers', true),
		
		/*
		 |--------------------------------------------------------------------------
		 | Address
		 |--------------------------------------------------------------------------
		 |
		 | Depending on where the house is built, you might want to specify the
		 | current address of the system to display it on the web application.
		 |
		 */
		'address' => env('address', '')
];