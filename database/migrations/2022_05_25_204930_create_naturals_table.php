<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNaturalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('naturals', function (Blueprint $table) {
            $table->id();

            $table->string('category');
            $table->string('classification')->nullable();
            $table->string('sub_category')->nullable();
            $table->text('area')->nullable();
            $table->string('ownership')->nullable();
            $table->string('other_name')->nullable();
            $table->string('scientific_name')->nullable();
            $table->string('class_origin')->nullable();
            $table->string('habitat')->nullable();
            $table->string('site_collected')->nullable();
            $table->string('seasonability')->nullable();
            $table->string('special_note')->nullable();
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
        Schema::dropIfExists('naturals');
    }
}
