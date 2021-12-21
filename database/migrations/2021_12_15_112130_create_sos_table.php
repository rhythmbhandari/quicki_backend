<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sos', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable();
            $table->text('message');
            $table->foreignId('booking_id')->nullable()->constrained("bookings")->cascadeOnUpdate()->nullOnDelete(); 
            $table->foreignId('created_by_id')->nullable();//->constrained("users")->cascadeOnUpdate()->nullOnDelete(); 
            $table->string('created_by_type')->comment('User roles like customer, rider, etc.');
            $table->json('location')->nullable()->comment('FORMAT: { "name":"Sanepa, Lalitpur", "latitude":"27.87652","longitude":"85.1234"}');
            $table->enum('status',['active','closed']); 
            $table->string('action_taken')->nullable();

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
        Schema::dropIfExists('sos');
    }
}
