<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exLog', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->unsignedBigInteger('from');
            $table->unsignedBigInteger('to');
            $table->double('amount', 10,5);
            $table->timestamps();
            $table->foreign('from')
                ->references('id')
                ->on('exCurrencies');
            $table->foreign('to')
                ->references('id')
                ->on('exCurrencies');
            $table->engine = 'InnoDB';
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
