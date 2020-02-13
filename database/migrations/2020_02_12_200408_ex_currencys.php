<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExCurrencys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exCurrencies', function (Blueprint $table) {
            $table->bigIncrements('id')
                ->unsigned();
            $table->string('code', 3);
            $table->string('currencyName');
            $table->string('currencySymbol', 10);
            $table->boolean('enabled');
            $table->timestamps();
            $table->softDeletes();
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
