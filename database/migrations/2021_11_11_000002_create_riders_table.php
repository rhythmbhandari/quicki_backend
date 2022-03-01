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

            $table->foreignId('user_id')->nullable()->constrained("users")->cascadeOnUpdate()->nullOnDelete();
            $table->integer('experience');      //Years
            $table->enum('trained', ['YES', 'NO'])->default('NO');
            $table->enum('status', ['active', 'in_active', 'blacklisted']);
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
