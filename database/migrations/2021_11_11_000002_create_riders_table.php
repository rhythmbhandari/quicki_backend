<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riders', function (Blueprint $table) {
            $table->id();

            // $table->unsignedBigInteger('user_id');
            // $table->foreign('user_id')->references('id')->on('users');
            // $table->unsignedBigInteger('vehicle_type');
            // $table->foreign('vehicle_type')->references('id')->on('vehicle_types');
            //$table->string('vehicle_number');
            //$table->foreignId('vehicle_id')->nullable()->constrained("vehicles")->cascadeOnUpdate()->nullOnDelete(); 

            // $table->string('license');          //License Document Image/File
            // $table->string('license_number');


            
            $table->foreignId('user_id')->nullable()->constrained("users")->cascadeOnUpdate()->nullOnDelete();   
      
            $table->integer('experience');      //Years
            $table->enum('trained', ['YES', 'NO'])->default('NO');
            $table->enum('status', ['active', 'in_active']);
            $table->timestamp('approved_at')->nullable();

            
            $table->string('device_token')->nullable();

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
        Schema::dropIfExists('riders');
    }
}
