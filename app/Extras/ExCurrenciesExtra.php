<?php

namespace App\Extras;

use App\ExCurrencies;


class ExCurrenciesExtra
{
      /*
       * @var array
       */
      private $cacheData = [];
      /*
       * @param string {code}
       * @return boolean||array
       */
      private function updateCheck($code){
          $currency = \App\ExCurrencies::where('code', $code)->first();
          if(isset($currency->id)){
               return $currency;
          }
          return false;
      }
      /*
       * @param string {currency}
       * @return integer {currency id}
       */
      private function updateOne($currency){
           $currencyModel = $this->updateCheck($currency['code']);
           if($currencyModel == false){
               $currencyModel = new ExCurrencies();
           }
           $currencyModel->code      = $currency['code'];
           $currencyModel->symbol    = $currency['symbol'];
           $currencyModel->name      = $currency['name'];
           $currencyModel->enabled   = true;
           $currencyModel->save();
           return $currencyModel->id;
      }
      /*
       *  @return boolean
       */
      public function updateAll(){
          foreach($this->cacheData as $k=>$v)
              $this->updateOne($v);
          return true;
      }
      /*
       * @param string {code}
       * @param array {container}
       * @return array
       */
      private function updateDataConvert($code, $container){
           return [
               "code"   => $code,
               "symbol" => $container['currencySymbol'],
               "name"   => $container['currencyName'],
           ];
      }
      /*
       * @param string {code}
       * @param array {data}
       * @return boolean
       */
      private function updateDataCheck($code, $data){
          if(strlen($code)!=3)
              return false;
          if(!isset($data['currencySymbol']))
              return false;
          if(!isset($data['currencyName']))
              return false;
         return true;
      }
      /*
       * @return boolean
       */
      public function updateFormApi(){
          $req_url = (
              'https://free.currconv.com/api/v7/currencies?apiKey='.
              config('ex.currencyconverterapi.key')
          );
          $response_json = file_get_contents($req_url);
          if(false !== $response_json) {
              try {
                  $response = json_decode(
                      $response_json,
                      true
                  );
                  if(!isset($response['results']))
                      return $response_json;
                  foreach($response['results'] as $k=>$v){
                      if($this->updateDataCheck($k, $v)===true)
                         $this->cacheData[$k]=$this->updateDataConvert($k, $v);
                  }
                  return true;
              }catch(Exception $e) {
                  return  $e;
              }
          } else {
              return false;
          }
     }
      /*
       * @return boolean
       */
     public function update(){
         $downloadResult = $this->updateFormApi();
         if( $downloadResult === true){
             $updateResult = $this->updateAll();
             if( $updateResult !== true)
                 return false;
         }else
             return false;
        return true;
     }
}
