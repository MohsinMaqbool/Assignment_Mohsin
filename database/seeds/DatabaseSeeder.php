<?php

use Illuminate\Database\Seeder;
use App\Customer;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        echo PHP_EOL , 'cleaning old data....';
        Customer::truncate();

        $this->call(CustomersTableSeeder::class);
    }
}
