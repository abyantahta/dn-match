<?php

namespace Database\Factories;

use App\Models\OrderDelivery;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderDeliveryFactory extends Factory
{
    protected $model = OrderDelivery::class;

    public function definition()
    {
        return [
            'plant_code' => $this->faker->numerify('PL###'),
            'shop_code' => $this->faker->numerify('SH###'),
            'part_category' => $this->faker->randomElement(['A', 'B', 'C']),
            'route' => $this->faker->numerify('R###'),
            'lp' => $this->faker->randomElement(['L1', 'L2', 'L3']),
            'trip' => $this->faker->numberBetween(1, 5),
            'vendor_code' => $this->faker->numerify('VC###'),
            'vendor_alias' => $this->faker->company,
            'vendor_site' => $this->faker->city,
            'vendor_site_alias' => $this->faker->citySuffix,
            'order_no' => $this->faker->numerify('ORD###'),
            'po_number' => $this->faker->numerify('PO#####'),
            'calc_date' => $this->faker->date,
            'order_date' => $this->faker->date,
            'order_time' => $this->faker->time,
            'del_date' => $this->faker->date,
            'del_time' => $this->faker->time,
            'del_cycle' => $this->faker->numberBetween(1, 3),
            'doc_no' => $this->faker->numerify('DOC###'),
            'rec_status' => $this->faker->randomElement(['Received', 'Pending']),
            'dn_type' => $this->faker->randomElement(['Type1', 'Type2']),
            'rec_date' => $this->faker->date,
            'rec_by' => $this->faker->name,
            'part_no' => $this->faker->numerify('P###'),
            'part_name' => $this->faker->word,
            'job_no' => $this->faker->numerify('J###'),
            'lane' => $this->faker->numberBetween(1, 100), // Hanya angka untuk kolom `lane`
            'qty_kbn' => $this->faker->randomFloat(2, 1, 100),
            'order_kbn' => $this->faker->randomFloat(2, 1, 100),
            'order_pcs' => $this->faker->numberBetween(1, 100),
            'qty_receive' => $this->faker->numberBetween(1, 100),
            'qty_balance' => $this->faker->numberBetween(1, 100),
            'cancel_status' => $this->faker->randomElement(['Yes', 'No']),
            'remark' => $this->faker->sentence,
        ];
    }
}
