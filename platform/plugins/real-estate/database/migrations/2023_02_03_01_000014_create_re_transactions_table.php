<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('re_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('credits')->nullable();
            $table->string('description', 255)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedDouble('type_id')->nullable();
            $table->unsignedBigInteger('payment_id')->nullable();
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
        Schema::dropIfExists('re_transactions');
    }
};
