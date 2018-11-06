<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskhistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taskhistories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('type')->nullable();
            $table->integer('assigned_id')->nullable();
            $table->string('status')->nullable();
            $table->string('task_name')->nullable();
            $table->string('task_detail')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->string('priority')->nullable();
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('taskhistories');
    }
}
