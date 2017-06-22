<?php

use Illuminate\Database\Seeder;

class DemoContent extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 50)->create()->each(function ($u) {
            $u->contents()->save(factory(App\Content::class)->make());
        });
    }
}
