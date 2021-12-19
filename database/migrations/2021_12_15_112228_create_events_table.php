<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->text('message');
            $table->foreignId('created_by_id')->nullable()->constrained("users")->cascadeOnUpdate()->nullOnDelete(); 
            $table->string('created_by_type')->comment('User roles like customer, rider, etc.');
            $table->foreignId('sos_id')->nullable()->constrained("sos")->cascadeOnUpdate()->nullOnDelete(); 

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
        Schema::dropIfExists('events');
    }
}
