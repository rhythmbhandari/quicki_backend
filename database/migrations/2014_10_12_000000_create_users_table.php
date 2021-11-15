<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            //$table->id();
            // $table->string('name');
            // $table->string('slug');
            // $table->string('email')->unique();
            // $table->timestamp('email_verified_at')->nullable();
            // $table->string('password');

            // $table->softDeletes();
            // $table->rememberToken();
            // $table->timestamps();

            $table->id();
           // $table->integer('vendor_id');
            $table->string('slug');
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
          //  $table->string('device_token')->nullable();
            $table->string('image')->nullable();
            $table->date('dob')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Others'])->nullable();
            

            $table->string('google_id')->nullable();
            $table->string('facebook_id')->nullable();
            $table->string('username',191)->unique()->nullable();;
            $table->string('phone')->unique()->nullable();
            $table->string('email',191)->unique()->nullable();
            $table->string('password');

            $table->enum('status',['active','in_active'])->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_logged_in')->nullable();
            $table->integer('no_of_logins')->nullable();
            $table->string('avatar')->nullable();

            $table->softDeletes();
            $table->bigInteger('last_updated_by')->nullable();
            $table->bigInteger('last_deleted_by')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
