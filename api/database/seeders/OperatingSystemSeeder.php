<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperatingSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('operating_systems')->insert([
            ['name' => 'android', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ios', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
