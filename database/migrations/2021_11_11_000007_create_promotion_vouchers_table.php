<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_vouchers', function (Blueprint $table) {
            $table->id();

            $table->string( 'slug' );

            $table->string( 'user_type' )->default('customer')->comment('User Roles like: customer or rider!');

            $table->string( 'image' )->nullable();

            // The voucher code
            $table->string( 'code' )->unique()->nullable( );

            // The human readable voucher code name
            $table->string( 'name' )->nullable();

            // The description of the voucher - Not necessary 
            $table->text( 'description' )->nullable( );

            // The number of uses currently
            $table->integer( 'uses' )->nullable( );

            // The max uses this voucher has
            $table->integer( 'max_uses' )->nullable( );

            // How many times a user can use this voucher.
            $table->integer( 'max_uses_user' )->default(1);

            // The type can be: voucher, discount, sale. What ever you want.
            $table->string( 'type' )->default('voucher')->comment('Could be anything feom: voucher, discount, sale, festive sale, etc.');

            // The amount to discount by in this example.
            $table->integer( 'worth' )->comment('Could be percent or amount');

            // Whether or not the voucher is a percentage or a fixed price. 
            $table->boolean( 'is_fixed' )->default( true );

            $table->json( 'eligible_user_ids' )->nullable()->comment('
                FORMAT:
                { 3,4,5,6  } #USER IDS TO WHOM THE PROMO APPLIES, the role is determined by promo type
            ');
            
            $table->json( 'price_eligibility' )->nullable()->comment('
                FORMAT: 
                {
                    { "price":"500","worth":"10" },  #If price spent is more than 500, then extra 10 is added to worth for the user
                    { "price":"5000","worth":"30" },
                    { "price":"10000","worth":"60" },
                }
            ');

            $table->json( 'distance_eligibility' )->nullable()->comment('
                FORMAT: 
                {
                    { "distance":"5000","worth":"10" },  #If travelled distance is more than 500m, then extra 10 is added to worth for the user
                    { "distance":"10000","worth":"30" },
                    { "distance":"20000","worth":"50" },
                }
            ');
            
            // When the voucher begins
            $table->timestamp( 'starts_at' )->nullable( );

            // When the voucher ends
            $table->timestamp( 'expires_at' )->nullable( );

            $table->enum('status',['active','in_active']); 

            // We like to horde data.
            $table->softDeletes( );


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotion_vouchers');
    }
}
