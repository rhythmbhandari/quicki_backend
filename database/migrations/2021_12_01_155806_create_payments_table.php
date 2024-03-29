<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            
            $table->foreignId('completed_trip_id')->nullable()->constrained("completed_trips")->cascadeOnUpdate()->nullOnDelete(); 
            $table->double('commission_amount');
            $table->double('original_commission')->nullable();

            //For applying discount on commissions of individual bookings
            // $table->foreignId('promotion_voucher_id')->nullable()->constrained("promotion_vouchers")->cascadeOnUpdate()->nullOnDelete();
            // $table->integer('discount_amount')->default(0);

            $table->enum('payment_status',['unpaid','paid'])->default('unpaid')->comment('Determines whether the rider got his share of income for this booking or not!');
            $table->enum('commission_payment_status',['unpaid','paid'])->default('unpaid')->comment('Determines whether the commission for this ride is paid to the admin on not!');

            $table->softDeletes();
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
        Schema::dropIfExists('payments');
    }
}
