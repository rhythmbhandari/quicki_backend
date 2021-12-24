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
            
            // $table->double('rate')->nullable();
            $table->integer('time_from');
            $table->integer('time_to');

            $table->double('rate');

            $table->foreignId('vehicle_type_id')->constrained("vehicle_types")->cascadeOnUpdate()->cascadeOnDelete();
            
            $table->enum('status',['active', 'in_active']);

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
