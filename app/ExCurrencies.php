<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExCurrencies extends Model
{
    use SoftDeletes;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $table = 'exCurrencies';
    protected $primaryKey = 'id';
    public function toCountry()
    {
        return $this->hasMany('App\ExCurrencyCountry');
    }
}
