<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnimalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->id("animal_id");
            $table->enum("type", ["cat", "dog", "bird"])->nullable(false)->default("cat");
            $table->date("birth_day")->nullable(false);
            $table->binary("sex")->nullable(false);
            $table->float("weight")->nullable(false);
            $table->string("country")->nullable(false);
            $table->string("owner")->nullable(false);
            $table->string("name")->unique()->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animals');
    }
}
