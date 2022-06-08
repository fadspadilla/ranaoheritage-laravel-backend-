<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImmovablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('immovables', function (Blueprint $table) {
            $table->id();

            $table->string('category');
            $table->string('type')->nullable();
            $table->string('land_area')->nullable();
            $table->string('structure_area')->nullable();
            $table->string('year_constructed')->nullable();
            $table->string('ownership')->nullable();
            $table->string('jurisdiction')->nullable();
            $table->string('legislation')->nullable();

            $table->unsignedBigInteger('heritage_id');
            $table->foreign('heritage_id')->references('id')->on('heritages')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

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
        Schema::dropIfExists('immovables');
    }
}
