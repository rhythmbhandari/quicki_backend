<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('image')->nullable();
          
            $table->double('commission')->comment('In Percent!');
            $table->string('capacity');
            $table->enum('status',['active','in_active']);
            $table->json('available_cities')->nullable()->comment('
                FORMAT: #CITIES where this vehicle type (city safari) operates
                { "pokhara", "butwal" }
            ');

            $table->double('default_surge_rate')->nullable()->comment('SURGE RATE applied either for shift surge!');

            $table->json('surge_rates')->nullable()->comment( 
                'FORMAT: {  
                    1 => 1.2,  #here 1 is customer:rider where customer is always more than rider as a value of 0 would have meant otherwise
                    2 => 1.5,
                    3 => 2
                } '
             );
            $table->integer('min_surge_customers')->default(30)->comment('Minimum number of customers in the rider:customers ratio for the surge to be applied');

            $table->integer('base_fare')->comment('The base price applied for the ride!');
            $table->integer('base_covered_km')->comment('The distance in km covered in the base fare!');
            // $table->integer('base_covered_min')->nullable()->comment('The duration in min covered in the base fare!');
            $table->enum('base_covers_duration',['no','yes'])->default('no')->comment('Determines if the initial estimated duration is covered by the base fare or not!');
            $table->integer('price_km')->comment('The price per km to be applied per additional km than what was covered in the base fare!');
            $table->integer('price_min')->comment('The price per min to be applied per additional min than what was estimated for the ride where base duration covered base is 1!');;
            $table->integer('min_charge')->comment('To be compared to the final calculated price!');

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
        Schema::dropIfExists('vehicle_types');
    }
}
