<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompletedTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('completed_trips', function (Blueprint $table) {
            $table->id();
       
            
            $table->foreignId('user_id')->nullable()->constrained("users")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('rider_id')->nullable()->constrained("riders")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained("bookings")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('location_id')->nullable()->constrained("locations")->cascadeOnUpdate()->nullOnDelete();

      

            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();

            // $table->string('origin');
            // $table->string('destination');
            $table->json('stoppage')->nullable();
            $table->integer('distance')->nullable();
            $table->integer('duration')->nullable();
            $table->integer('passenger_number')->nullable()->default(1);
            $table->string('profile_img_user')->nullable();
            $table->string('profile_img_rider')->nullable();
            $table->enum('status',['completed','cancelled']);

            
            $table->integer('price')->default(0);
            $table->string('payment_type')->nullable()->default('CASH');

           // $table->string('cancelled_by')->nullable();
            $table->string('cancelled_by_type')->nullable()->comment('customer or rider');    //customer or rider
            $table->foreignId('cancelled_by_id')->nullable()->constrained("users")->cascadeOnUpdate()->nullOnDelete();
            $table->string('cancel_message')->nullable();

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
        Schema::dropIfExists('completed_trips');
    }
}
