<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // $table->unsignedBigInteger('ride_id')->nullable();
            // $table->foreign('ride_id')->references('id')->on('booking')->onDelete('cascade');
            // $table->unsignedBigInteger('rider_id')->nullable();
            // $table->unsignedBigInteger('user_id')->nullable();
            
            $table->foreignId('booking_id')->nullable()->constrained("bookings")->cascadeOnUpdate()->nullOnDelete();    //ride id
            $table->foreignId('rider_id')->nullable()->constrained("riders")->cascadeOnUpdate()->nullOnDelete();    
            $table->foreignId('user_id')->nullable()->constrained("users")->cascadeOnUpdate()->nullOnDelete();

            $table->enum('reviewed_by_role',['user','rider']);

            $table->double('rate')->nullable();
            $table->date('ride_date')->nullable();
            $table->longText('comment')->nullable();

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
        Schema::dropIfExists('reviews');
    }
}
