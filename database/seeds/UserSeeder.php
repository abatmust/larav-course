<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $number_of_users = (int)$this->command->ask("how many users do you want ?", 5);
        factory(App\User::class,$number_of_users)->create();
    }
}
