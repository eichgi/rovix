<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        //$this->call(ResourcesTableSeeder::class);
        $this->call(TypesTableSeeder::class);
        $this->call(FormatTableSeeder::class);
        $this->call(LanguagesTableSeeder::class);
    }
}
