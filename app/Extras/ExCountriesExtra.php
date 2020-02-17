<?php

namespace App\Extras;

use App\ExCountries;
use App\Extras\ExCountryCurrencyExtra;


class ExCountriesExtra
{
      /*
       * @var string
       */
      private $countryCurrency;
      /*
       * @var array 
       */
      private $cacheData = [];
      /*
       * @param string {$codeTwo}
       * @return boolean||array
       */
      private function updateCheck($codeTwo){
          $country = \App\ExCountries::where('alpha2', $codeTwo)->first();
          if(isset($country->id)){
               return $country;
          }
          return false;
      }
      /*
       * @param array {$container}
       * @return string
       */
      private function updateOne($container){
           $countryModel = $this->updateCheck($container['alpha2']);
           if($countryModel == false){
               $countryModel = new ExCountries();
           }
           $countryModel->alpha2 = $container['alpha2'];
           $countryModel->alpha3 = $container['alpha3'];
           $countryModel->name   = $container['name'];
           $countryModel->save();
           $this->addCurrencyToCountry($countryModel->id, $container['currency']);
           return $countryModel->id;
      }
      /*
       * @param integer {$countryId}
       * @param string {$currency}
       * @return string
       */
      public function addCurrencyToCountry($countryId, $currency){
           $currencyModel = \App\ExCurrencies::where('code', $currency)
               ->first();
           if(!isset($currencyModel->id)){
               return false;
           }
           $this->countryCurrency->add($countryId, $currencyModel->id);

      }
      /*
       * @return boolean
       */
      public function updateAll(){
          foreach($this->cacheData as $k=>$v)
              $this->updateOne($v);
          return true;
      }
      /*
       * @param string {$codeTwo}
       * @param array {$container}
       * @return array
       */
      private function updateDataConvert($codeTwo, $container){
           return [
               "alpha2"   => $codeTwo,
               "alpha3"   => $container['alpha3'],
               "name"     => $container['name'],
               "currency" => $container['currencyId'],
           ];
      }
      /*
       * @param string {$codeTwo}
       * @param array {$container}
       * @return boolean 
       */
      private function updateDataCheck($codeTwo, $container){
          if(strlen($codeTwo)!=2)
              return false;
          if(!isset($container['alpha3']))
              return false;
          if(strlen($container['alpha3'])!=3)
              return false;
          if(!isset($container['name']))
              return false;
          if(!isset($container['currencyId']))
              return false;
         return true;
      }
      /*
       * @return boolean 
       */
      public function updateFormApi(){
          $req_url = (
              'https://free.currconv.com/api/v7/countries?apiKey='.
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
     public function __construct(){
         $this->countryCurrency = new ExCountryCurrencyExtra();

     }
}
