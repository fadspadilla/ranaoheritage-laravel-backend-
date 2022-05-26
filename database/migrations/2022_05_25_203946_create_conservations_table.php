<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conservations', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('content')->nullable();

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
        Schema::dropIfExists('conservations');
    }
}
