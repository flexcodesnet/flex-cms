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
        Schema::create('re_properties', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable();
            $table->string('summary', 500)->nullable();
            $table->longText('content')->nullable();
            $table->unsignedBigInteger('type_id')->nullable();

            $table->string('image', 255)->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->integer('number_bedroom')->nullable();
            $table->integer('number_bathroom')->nullable();
            $table->integer('number_floor')->nullable();
            $table->decimal('square', 15)->nullable();
            $table->decimal('price', 15)->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->string('period', 30)->default('month');
            $table->string('moderation_status', 60)->default('pending');
            $table->date('expire_date')->nullable();

            $table->unsignedBigInteger('views')->default(0)->nullable();
            $table->unsignedBigInteger('status_id')->nullable();

            $table->unsignedBigInteger('address_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('location', 255)->nullable();
            $table->string('latitude', 25)->nullable();
            $table->string('longitude', 25)->nullable();

//            $table->morphs('author');
            $table->boolean('is_featured')->default(0)->nullable();
            $table->boolean('is_auto_renew')->default(false)->nullable();
            $table->boolean('is_never_expired')->default(false)->nullable();
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
        Schema::dropIfExists('re_properties');
    }
};
