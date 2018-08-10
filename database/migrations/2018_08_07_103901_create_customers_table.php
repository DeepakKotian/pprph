<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('language')->nullable();
            $table->string('gender')->nullable();
            $table->string('company')->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->timestamp('dob')->nullable();
            $table->string('address')->nullable();
            $table->string('zip')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('nationality')->nullable();
            $table->string('email')->nullable();
            $table->string('email_office')->nullable();
            $table->string('telephone')->nullable();
            $table->string('mobile')->nullable();
            $table->boolean('is_family')->default(false);
            $table->integer('parent_id')->default(0);
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
        Schema::dropIfExists('customers');
    }
}
