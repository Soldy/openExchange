<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ExchangeHelper;

class ExchangeController extends Controller
{
    use exchangeHelper;
    /*
     * @param string {$from}
     * @param string {$to}
     * @param double {$amount}
     * @return  
     */
    public function getExchange ($amount, $from, $to){
        $result = $this->exchange($from, $to, $amount);
        if($result == false)
             return response()->json([
                 "error" =>  1,
                 "msg"   =>   "currency code ". implode(",",$this->notSupported)." not supported"
              ]);
        return response()->json([
            "error"     => 0,
            "amount"    => $result,
            "fromCache" => $this->fromCache

        ]);
    }

    public function getCacheClear (){
        $this->cacheClear();    
        return response()->json([
            "error" => 0,
            "msg" => "OK"
        ]);
    }

    public function getInfo (){
        return response()->json([
            "error" => 0,
            "msg" => "API writen by 501dy"
        ]);
    }
}

