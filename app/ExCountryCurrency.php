<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExCountryCurrency extends Model
{
    use SoftDeletes;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $table = 'exCountryCurrency';
    protected $primaryKey = 'id';

    public function country()
    {
        return $this->belongsTo('App\ExCountries', 'countryId');
    }
    public function currency()
    {
        return $this->belongsTo('App\ExCurrencies', 'currencyId');
    }



}
