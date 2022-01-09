<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSentNewslettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sent_newsletters', function (Blueprint $table) {
            $table->id();

            $table->foreignId('newsletter_id')->nullable()->constrained("newsletters")->cascadeOnUpdate()->nullOnDelete();

            $table->json('subscriber_ids')->nullable();

            $table->timestamp('sent_at')->nullable();
            
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
        Schema::dropIfExists('sent_newsletters');
    }
}
