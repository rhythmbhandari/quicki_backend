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
            
            // $table->unsignedBigInteger('user_id');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->unsignedBigInteger('rider_id')->nullable();
            // $table->foreign('rider_id')->references('id')->on('users')->onDelete('cascade');
            // $table->unsignedBigInteger('location_id');
            // $table->foreign('location_id')->references('id')->on('user_locations')->onDelete('cascade');
            // $table->unsignedBigInteger('book_id');
            // $table->foreign('book_id')->references('id')->on('booking')->onDelete('cascade');

            
            $table->foreignId('user_id')->nullable()->constrained("users")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('rider_id')->nullable()->constrained("riders")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained("bookings")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('location_id')->nullable()->constrained("locations")->cascadeOnUpdate()->nullOnDelete();

            $table->string('origin');
            $table->string('destination');
            $table->json('stoppage')->nullable();
            $table->string('distance')->nullable();
            $table->string('duration')->nullable();
            $table->integer('passenger_number')->nullable()->default(1);
            $table->string('profile_img_user')->nullable();
            $table->string('profile_img_rider')->nullable();
            $table->enum('status',['completed','cancelled']);

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
