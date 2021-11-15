<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('slug');

            // $table->unsignedBigInteger('rider_id');
            // $table->foreign('rider_id')->references('id')->on('riders');
            // $table->unsignedBigInteger('vehicle_type');
            // $table->foreign('vehicle_type')->references('id')->on('vehicle_types');
            // $table->string('license_number')->nullable();

            
            $table->foreignId('rider_id')->nullable()->constrained("riders")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('vehicle_type_id')->nullable()->constrained("vehicle_types")->cascadeOnUpdate()->nullOnDelete();

            $table->string('vehicle_number');
            $table->string('image')->nullable();
            $table->string('make_year')->nullable();
            $table->string('vehicle_color')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->enum('status',['active', 'in_active'])->nullable();

            
            $table->softDeletes();
            $table->timestamp('last_deleted_by')->nullable();
            $table->timestamp('last_updated_by')->nullable();
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
        Schema::dropIfExists('vehicles');
    }
}
