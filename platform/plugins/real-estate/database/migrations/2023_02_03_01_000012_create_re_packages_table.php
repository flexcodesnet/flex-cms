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
        Schema::create('re_packages', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable();
            $table->unsignedDouble('price', 15, 2)->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->unsignedBigInteger('percent_save')->default(0)->nullable();
            $table->unsignedBigInteger('number_of_listings')->nullable();
            $table->unsignedBigInteger('account_limit')->nullable();
            $table->unsignedBigInteger('order')->default(0)->nullable();
            $table->boolean('is_default')->default(0)->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
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
        Schema::dropIfExists('re_packages');
    }
};
