<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExCurrencyHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exCurrencyHistory', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->unsignedBigInteger('from');
            $table->unsignedBigInteger('to');
            $table->double('rate', 10,5);
            $table->unsignedBigInteger('timeMoment');
            $table->unsignedInteger('type');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('from')
                ->references('id')
                ->on('exCurrencies');
            $table->foreign('to')
                ->references('id')
                ->on('exCurrencies');
            $table->engine = 'InnoDB';
        }); 
        //
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
