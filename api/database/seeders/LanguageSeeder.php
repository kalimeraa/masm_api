<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('languages')->insert([
            ['locale' => 'en', 'created_at' => now(), 'updated_at' => now()],
            ['locale' => 'tr', 'created_at' => now(), 'updated_at' => now()],
            ['locale' => 'es', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}
