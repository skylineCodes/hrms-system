<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins_profile', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('middlename')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->string('nationality')->nullable();
            $table->string('sex')->nullable();
            $table->string('dob')->nullable();
            $table->text('description')->nullable();
            $table->integer('status')->default(0);
            $table->bigInteger('admin_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins_profile');
    }
}
