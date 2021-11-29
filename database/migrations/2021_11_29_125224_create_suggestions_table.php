<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuggestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suggestions', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();

            $table->string('text');

            $table->string('icon')->nullable();

            $table->string('type')->nullable()
            ->comment('Suggestion type. For ex: booking_cancel_by_rider, booking_cancel_by_user, review_by_rider, review_by_user.');

            $table->string('category')->nullable()
            ->comment('Suggesstion category. For Ex: positive_review, negative_review');


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
        Schema::dropIfExists('suggestions');
    }
}
