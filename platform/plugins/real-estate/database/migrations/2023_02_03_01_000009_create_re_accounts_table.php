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
        Schema::create('re_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 120)->nullable();
            $table->string('last_name', 120)->nullable();
            $table->text('description')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('email')->unique();
            $table->string('username', 60)->unique()->nullable();
            $table->string('password');
            $table->string('company', 255)->nullable();
            $table->unsignedBigInteger('avatar_id')->nullable();
            $table->date('dob')->nullable();
            $table->string('phone', 25)->nullable();
            $table->unsignedBigInteger('credits')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->string('email_verify_token', 120)->nullable();
            $table->boolean('is_featured')->default(0)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('re_accounts');
    }
};
