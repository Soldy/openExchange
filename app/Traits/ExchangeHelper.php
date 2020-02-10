<?php

namespace App\Traits;

use App\Exchange;

trait exchangeHelper 
{
    /*
     * var @array
     */
     private $notSupported =[];
    /*
     * var @boolean
     */
     private $error = false;
    /*
     * var@integer ( as a boolean)
     */
     private $fromCache = 1;
    /**
     * Check outdated data and tigger the cache  update.
     *
     * @return  Integer
     */
    private function update (){
        $exchanges = \App\Exchange::where(
            'updated_at', 
            '<', 
            date('Y-m-d H:i:s',strtotime('-1 minute'))
        )->select(
            'from'
        )->groupBy('from')->get();
        if(count($exchanges)>0)
            for($i = 0 ; count($exchanges) > $i ; $i++)
                $this->getFormApi($exchanges[$i]->form);
        return count($exchanges);
    }
    /**
     * Save or update currency pair.
     * @param string {$from}
     * @param string {$to}
     * @param double {$rate}
     * @return integer 
     */
    private function save($from, $to, $rate){
        $exchange = $this->check($from,$to);
        if(!$exchange){
            $exchange = new \App\Exchange();
        }
        $exchange->from = $from;
        $exchange->to = $to;
        $exchange->rate = $rate;
        $exchange->save();
        return $exchange->id;
    }
    /**
     * Execute the console command.
     * @param string {$from}
     * @param string {$to}
     * @return mixed
     */
    public function check($from, $to){
        $exchange = \App\Exchange::where(function ($query) use ($to, $from) {
            $query->where('to', '=', $to)
                   ->where('from', '=', $from);
            })->orWhere(function ($query) use ($from, $to){
            $query->where('to', '=', $from)
                   ->where('from', '=', $to);

            })->first();
        if(isset($exchange->id))
           return $exchange;
        return false;
    }
    /*
     * @param string {$from}
     * @param string {$to}
     * @param double {$amount}
     * @return float 
     */
    private function exchange($from, $to, $amount){
        if(!in_array($from, \App\Exchange::TYPES, true))
          $this->notSupported[]=$from;
        if(!in_array($to, \App\Exchange::TYPES, true))
          $this->notSupported[]=$to;
        if(count($this->notSupported)>0) 
            return false;
        if($from == $to)
           return $amount;
        $exchange = $this->check($from, $to);
        if( $exchange == false ){
            $this->getFormApi($from);
            $exchange = $this->check($from, $to);
        }
        
        if($from == $exchange->from){
            return round(($amount * $exchange->rate), 2);
        }
        if($to == $exchange->from){
            return round(($amount / $exchange->rate), 2);
        }
        return false;

    }
    /*
     * @return void
     */
    private function getFormApi($currency="NONE"){
        $this->fromApi = 0;
        $req_url = 'https://api.exchangeratesapi.io/latest?base='.$currency;
        $response_json = file_get_contents($req_url);
        if(false !== $response_json) {
            try {
                 $response = json_decode(
                     $response_json,
                     true
                 );
                 foreach(\App\Exchange::TYPES as $type){
                     if($type == $currency)
                         continue;
                     $this->save(
                         $currency,
                         $type,
                         round(
                            $response['rates'][$type], 
                            5
                         )
                     ); 
                 }
                 return true;
            }catch(Exception $e) {
 
            }

        }
    }

    /*
     * @return void
     */
    private function cacheClear(){
        \App\Exchange::truncate();
    }
}

