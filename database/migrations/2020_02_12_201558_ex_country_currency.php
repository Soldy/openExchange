<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExCountryCurrency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exCountryCurrency', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->unsignedBigInteger('countryId');
            $table->unsignedBigInteger('currencyId');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('countryId')
                ->references('id')
                ->on('exCountries');
            $table->foreign('currencyId')
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
