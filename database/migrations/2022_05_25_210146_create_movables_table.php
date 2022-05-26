<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movables', function (Blueprint $table) {
            $table->id();

            $table->string('category');
            $table->string('type')->nullable();
            $table->string('type_sub')->nullable();
            $table->string('date')->nullable();
            $table->string('age')->nullable();
            $table->string('owner')->nullable();
            $table->string('acquisition')->nullable();
            $table->string('religion')->nullable();
            $table->string('artist')->nullable();
            $table->string('nationality')->nullable();
            $table->string('prev_owner')->nullable();
            $table->string('curr_owner')->nullable();
            $table->string('volume')->nullable();            
            $table->string('arrangement')->nullable();            
            $table->string('contact_person')->nullable();            

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
        Schema::dropIfExists('movables');
    }
}
