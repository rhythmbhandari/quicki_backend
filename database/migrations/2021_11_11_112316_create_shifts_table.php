<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            
            $table->double('rate')->nullable();
            $table->integer('time_from')->nullable();
            $table->integer('time_to')->nullable();

            $table->foreignId('vehicle_type_id')->nullable()->constrained("vehicle_types")->cascadeOnUpdate()->cascadeOnDelete();
            
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
        Schema::dropIfExists('shifts');
    }
}
