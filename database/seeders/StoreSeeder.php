<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Store::create([
            'name' => 'My Store',
            'address' => 'Jl. Anggrek Nomor 2',
            'phone_number' => '089812345678',
        ]);
    }
}
