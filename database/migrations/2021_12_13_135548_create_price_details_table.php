<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained("bookings")->cascadeOnUpdate()->nullOnDelete(); 
            $table->foreignId('completed_trip_id')->nullable()->constrained("completed_trips")->cascadeOnUpdate()->nullOnDelete(); 

            $table->double('base_fare')->nullable();
            $table->integer('base_covered_km')->nullable();
            $table->double('minimum_charge')->nullable();
            $table->double('price_per_km')->nullable();
            $table->integer('charged_km')->nullable();
            $table->double('price_after_distance')->nullable();
            $table->double('shift_surge')->nullable();
            $table->double('density_surge')->nullable();
            $table->double('surge_rate')->nullable();
            $table->double('price_per_km_after_surge')->nullable();
            $table->double('surge')->nullable();
            $table->double('price_after_surge')->nullable();
            $table->double('app_charge_percent')->nullable();
            $table->double('app_charge')->nullable();
            $table->double('price_after_app_charge')->nullable();
            $table->double('price_per_min')->nullable();
            $table->double('duration_charge')->nullable();
            $table->double('price_after_duration')->nullable();
            $table->double('price_after_base_fare')->nullable();
            
            $table->integer('total_price')->nullable();

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
        Schema::dropIfExists('price_details');
    }
}
