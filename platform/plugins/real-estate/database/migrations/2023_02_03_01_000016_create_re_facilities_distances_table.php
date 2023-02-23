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
        Schema::create('re_facilities_distances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('facility_id')->nullable();
            $table->morphs('reference');
            $table->string('distance', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('re_facilities_distances');
    }
};
