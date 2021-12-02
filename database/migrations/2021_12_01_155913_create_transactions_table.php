<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->double('amount');
            $table->timestamp('transaction_date');

            $table->string('creditor_type')->comment('User Roles: customer, rider, or admin');
            $table->foreignId('creditor_id')->nullable()->constrained("users")->cascadeOnUpdate()->nullOnDelete(); 
            $table->string('debtor_type')->comment('User Roles: customer, rider, or admin');
            $table->foreignId('debtor_id')->nullable()->constrained("users")->cascadeOnUpdate()->nullOnDelete();   

            $table->enum('payment_mode',['online','offline'])->default('offline');
            $table->string('payment_gateway_type')->nullable()->comment('Esewa or Khalti');
            $table->string('payment_gateway_user_id')->nullable()->comment('For Esewa, it might be the creditor\' mobile number!');
            $table->string('payment_gateway_transaction_amount')->nullable()->comment('Amount transferred via payment gateway!');

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
        Schema::dropIfExists('transactions');
    }
}
