<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrderDelivery;

class OrderDeliverySeeder extends Seeder
{
    public function run()
    {
        // Membuat 100 data palsu untuk tabel OrderDelivery
        OrderDelivery::factory()->count(100)->create();
    }
}
