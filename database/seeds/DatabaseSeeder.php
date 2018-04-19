<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            OptionTableSeeder::class,
            UsersTableSeeder::class,
            // ContactsTableSeeder::class,
            Shopping_cartTableSeeder::class,
            ProductTableSeeder::class,
        ]);
    }
}
