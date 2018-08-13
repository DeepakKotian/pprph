<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePolicyDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('policy_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('insurance_ctg_id');
            $table->integer('provider_id');
            $table->integer('customer_id');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->string('document_name')->nullable();
            $table->string('policy_number')->unique();
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
        Schema::dropIfExists('policy_detail');
    }
}
