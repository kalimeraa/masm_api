<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('apps')->insert([
            ['name' => 'facebook', 'endpoint' => 'https://api.facebook.com/notifysubscription', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'instagram', 'endpoint' => 'https://api.instagram.com/notifysubscription', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'whatsapp', 'endpoint' => 'https://api.whatsapp.com/notifysubscription', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}
