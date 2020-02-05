<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exchange extends Model
{
    const TYPES = [
        "CAD",  
        "JPY", 
        "USD", 
        "GBP",
        "EUR", 
        "RUB", 
        "HKD",
        "CHF" 
    ];
    use SoftDeletes;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $table = 'exchange';
    protected $primaryKey = 'id';
}
    
