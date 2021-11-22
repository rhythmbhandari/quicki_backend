<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->text('description')->nullable();
            $table->enum('required', ['1','0'])->nullable()->default('0');
            $table->enum('group', ['general','products','banners', 'checkout', 'cart', 'header', 'footer', 'social', 'delivery_price' ,'others'])->nullable()->default('others');
            $table->enum('data_type', ['text','image','link', 'integer', 'double', 'json', 'number','numeric', 'email', 'icon'])->nullable()->default('text');

            $table->double('image_ratio')->nullable();

            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->bigInteger('last_updated_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('last_updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->softDeletes();
            $table->bigInteger('last_deleted_by')->unsigned()->index()->nullable();
            $table->foreign('last_deleted_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
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
        Schema::dropIfExists('settings');
    }
}
