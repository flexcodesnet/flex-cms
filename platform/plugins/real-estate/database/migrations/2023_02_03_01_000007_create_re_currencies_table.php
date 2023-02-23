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
        Schema::create('re_currencies', function (Blueprint $table) {
            $table->id();
            $table->string('title', 60)->nullable();
            $table->string('symbol', 10)->nullable();
            $table->unsignedTinyInteger('is_prefix_symbol')->default(0)->nullable();
            $table->unsignedTinyInteger('decimals')->default(0)->nullable();
            $table->unsignedBigInteger('order')->default(0)->nullable();
            $table->boolean('is_default')->default(0)->nullable();
            $table->double('exchange_rate')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('re_currencies');
    }
};
