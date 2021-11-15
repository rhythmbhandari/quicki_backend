<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiderLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rider_locations', function (Blueprint $table) {
            $table->id();

            
            // $table->unsignedBigInteger('rider_id');
            // $table->foreign('rider_id')->references('id')->on('riders')->onDelete('cascade');

            $table->double('longitude');
            $table->double('latitude');
            $table->foreignId('rider_id')->nullable()->constrained("riders")->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('status',['active', 'in_active'])->nullable();

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
        Schema::dropIfExists('rider_locations');
    }
}
