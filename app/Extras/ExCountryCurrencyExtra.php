<?php

namespace App\Extras;

use App\ExCountryCurrency;


class ExCountryCurrencyExtra
{
      /*
       * @param string {$country}
       * @param string {$currency}
       * @return boolean||integer
       */
      private function addCheck($country, $currency){
          $data = \App\ExCountryCurrency::where('countryId', $country)
              ->where('currencyId', $currency)
              ->first();
          if(isset($data->id)){
               return $data;
          }
          return false;
      }
      /*
       * @param string {$country}
       * @param string {$currency}
       * @return integer
       */
      private function addOne($country, $currency){
           $model = $this->addCheck($country, $currency);
           if($model == false){
               $model = new ExCountryCurrency();
               $model->countryId   = $country;
               $model->currencyId  = $currency;
               $model->save();
           }
           return $model->id;
      }
      public function add($country, $currency){
          $this->addOne($country, $currency);
      }


}
