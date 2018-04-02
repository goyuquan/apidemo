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
        // factory(App\User::class, 62)->create()->each(function ($u) {
        //     $u->contacts()->save(factory(App\Contact::class)->make());
        // });

        factory(App\User::class, 60)->create();
    }
}
