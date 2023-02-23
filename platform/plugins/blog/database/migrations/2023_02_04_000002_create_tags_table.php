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
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->nullable();
            $table->string('title', 255)->nullable();
            $table->string('summary', 500)->nullable();
            $table->string('image', 255)->nullable();

            $table->morphs('author');
            $table->unsignedBigInteger('status_id')->nullable();
            $table->unsignedBigInteger('order')->default(0)->nullable();
            $table->unsignedBigInteger('views')->default(0)->nullable();

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
        Schema::dropIfExists('tags');
    }
};
