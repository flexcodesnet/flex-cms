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
        Schema::create('re_consults', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable();
            $table->string('email', 60);
            $table->string('phone', 60);
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('property_id')->nullable();
            $table->longText('content')->nullable();
            $table->unsignedBigInteger('status_id');
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
        Schema::dropIfExists('re_consults');
    }
};
