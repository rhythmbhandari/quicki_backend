<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;




class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable();
            $table->text('message');
            $table->string('image')->nullable();
            $table->integer('recipient_id')->nullable();//->nullable()->constrained("users")->cascadeOnUpdate()->nullOnDelete(); 
            $table->text('recipient_device_token')->nullable();
            $table->string('recipient_type')->default('customer')->comment('User Roles like customer, rider, admin, etc.');
            $table->enum('recipient_quantity_type',['individual','some','all'])->default('individual')->comment('Allowed values individual, some or all.');
            $table->string('notification_type')->default('push_notification');
            $table->timestamp('read_at')->nullable();

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
        Schema::dropIfExists('notifications');
    }
}
