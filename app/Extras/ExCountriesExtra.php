<?php

namespace App\Extras;

use App\ExCountries;


class ExCountriesExtra
{
      private $cacheData = [];
      private function updateCheck($codeTwo){
          $country = \App\ExCountries::where('alpha2', $codeTwo)->first();
          if(isset($country->id)){
               return $country;
          }
          return false;
      }
      private function updateOne($country){
           $countryModel = $this->updateCheck($country['alpha2']);
           if($countryModel == false){
               $countryModel = new ExCountries();
           }
           $countryModel->alpha2 = $country['alpha2'];
           $countryModel->alpha3 = $country['alpha3'];
           $countryModel->name   = $country['name'];
           $countryModel->save();
           return $countryModel->id;
      }
      public function updateAll(){
          foreach($this->cacheData as $k=>$v)
              $this->updateOne($v);
          return true;
      }
      private function updateDataConvert($codeTwo, $container){
           return [
               "alpha2" => $codeTwo,
               "alpha3" => $container['alpha3'],
               "name"   => $container['name'],
           ];
      }
      private function updateDataCheck($codeTwo, $data){
          if(strlen($codeTwo)!=2)
              return false;
          if(!isset($data['alpha3']))
              return false;
          if(strlen($data['alpha3'])!=3)
              return false;
          if(!isset($data['name']))
              return false;
         return true;
      }
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
