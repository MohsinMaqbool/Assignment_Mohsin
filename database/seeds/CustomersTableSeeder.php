<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Customer;
use Illuminate\Support\Facades\DB;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo PHP_EOL , 'inserting 100000 records in customers table....';

        	
    	$no_of_data = 100000;
		$test_data = array();
		for ($i = 0; $i < $no_of_data; $i++){

		  $test_data[$i]['name']    = Str::random(10);
		  $test_data[$i]['age']     = rand(10,59);
		  $test_data[$i]['address'] = 'Sreet'.rand(0,9).Str::random(6);
		  $test_data[$i]['phone']   = '0302-'.mt_rand(1000000, 9999999);

		}
		$chunk_data = array_chunk($test_data, 1000);
		if (isset($chunk_data) && !empty($chunk_data)) {
		  foreach ($chunk_data as $chunk_data_val) {
		     DB::table('customers')->insert($chunk_data_val);
		  }
		}

    }
}
