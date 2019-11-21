<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Compras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras', function (Blueprint $table){
            $table->increments('id');
            $table->integer('proveedores_id')->unsigned();
            $table->foreign('proveedores_id')->references('id')->on('proveedores')->onDelete('restrict')->onUpdate('restrict');
            $table->string('factura')->nullable();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}