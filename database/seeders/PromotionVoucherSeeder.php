<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;

use App\Modules\Models\PromotionVoucher;

class PromotionVoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PromotionVoucher::create([
            'user_type' => 'customer',
            'code' => '#9816810976C',
            'name' => 'TEST CUSTOMER VOUCHER',
            'description' => 'This is just a test voucher for customers!',
            'uses' => 0,
            'max_uses' => 50,
            'max_uses_user' => 1,
            'type' => 'discount',
            'worth' => 10,
            'is_fixed' => true,
            'eligible_user_ids' => null,
            'price_eligibility' => [
                ["price"=>500, "worth"=>10],
                ["price"=>5000, "worth"=>30],
                ["price"=>10000, "worth"=>60],
            ],
            
            'distance_eligibility' =>  [
                ["distance"=>5000, "worth"=>10],
                ["distance"=>10000, "worth"=>30],
                ["distance"=>20000, "worth"=>50],
            ],
            'starts_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addMonth(),
            'status' => 'active',
        ]);


        PromotionVoucher::create([
            'user_type' => 'rider',
            'code' => '#9816810976R',
            'name' => 'TEST RIDER VOUCHER',
            'description' => 'This is just a test voucher for riders!',
            'uses' => 0,
            'max_uses' => 50,
            'max_uses_user' => 1,
            'type' => 'discount',
            'worth' => 10,
            'is_fixed' => true,
            'eligible_user_ids' => null,
            'price_eligibility' => [
                ["price"=>500, "worth"=>10],
                ["price"=>5000, "worth"=>30],
                ["price"=>10000, "worth"=>60],
            ],
            
            'distance_eligibility' =>  [
                ["distance"=>5000, "worth"=>10],
                ["distance"=>10000, "worth"=>30],
                ["distance"=>20000, "worth"=>50],
            ],
            'starts_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addMonth(),
            'status' => 'active',
        ]);


    }
}
