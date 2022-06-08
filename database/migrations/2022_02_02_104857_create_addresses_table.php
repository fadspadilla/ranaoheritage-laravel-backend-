<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->unsignedBigInteger('mun_id')->nullable();
            $table->foreign('mun_id')->references('id')->on('municipalities')
                  ->onUpdate('cascade')
                  ->onDelete('set null');
            $table->unsignedBigInteger('loc_id')->nullable();
            $table->foreign('loc_id')->references('id')->on('locations')
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
        Schema::dropIfExists('addresses');
    }
}
