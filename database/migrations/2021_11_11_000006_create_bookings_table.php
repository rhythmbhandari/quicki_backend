<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            
            // $table->unsignedBigInteger('vehicle_type');
            // $table->foreign('vehicle_type')->references('id')->on('vehicle_types')->onDelete('cascade');
            // $table->unsignedBigInteger('user_id');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->unsignedBigInteger('rider_id')->nullable();
            // $table->foreign('rider_id')->references('id')->on('users')->onDelete('cascade');
            // $table->unsignedBigInteger('vehicle_id')->nullable();
            // $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            // $table->unsignedBigInteger('location_id')->nullable();
            // $table->foreign('location_id')->references('id')->on('user_locations');
            // $table->foreignId('vehicle_id')->constrained("vehicles")->cascadeOnUpdate()->nullOnDelete();

            
            $table->foreignId('user_id')->nullable()->constrained("users")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('vehicle_type_id')->nullable()->constrained("vehicle_types")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('rider_id')->nullable()->constrained("users")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('location_id')->nullable()->constrained("locations")->cascadeOnUpdate()->nullOnDelete();

            $table->string('origin');
            $table->string('destination');
            $table->double('distance');     //in meters
            $table->double('duration');     //in seconds
            $table->integer('passenger_number')->nullable()->default(1);
            // $table->string('name')->nullable();
            // $table->string('phone_number')->nullable();
            $table->integer('status')->default(null);
            $table->integer('ride_status')->nullable()->default(null);
            $table->float('price')->default(0);
            $table->string('payment_type');


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
        Schema::dropIfExists('bookings');
    }
}
