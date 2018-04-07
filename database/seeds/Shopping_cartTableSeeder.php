<?php

use Illuminate\Database\Seeder;

class Shopping_cartTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Shopping_cart::class, 100)->create();
    }
}
