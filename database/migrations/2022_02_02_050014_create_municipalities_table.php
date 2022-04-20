<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMunicipalitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('municipalities', function (Blueprint $table) {
            $table->id();            
            $table->string('name');            
            $table->text('description')->nullable();    
            $table->string('seal')->nullable();
            $table->unsignedBigInteger('prov_id')->nullable();
            $table->foreign('prov_id')->references('id')->on('provinces')
                  ->onUpdate('cascade')
                  ->onDelete('set null');
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
        Schema::dropIfExists('municipalities');
    }
}
