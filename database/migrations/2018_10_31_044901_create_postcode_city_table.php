<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostcodeCityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postcode_city', function (Blueprint $table) {
            $table->increments('id');
            $table->string('plz');
            $table->string('city');
            $table->string('canton_id');
            $table->string('canton_de');
            $table->string('canton_fr');
            $table->string('abbr');
            $table->string('land');
            $table->string('pays');
            $table->string('paese');
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
        Schema::dropIfExists('postcode_city');
    }
}
