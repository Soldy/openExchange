<?php

namespace App\Extras;

use Symfony\Component\HttpFoundation\Response;

class OutputHelper
{
     private $module   = 'none';
     private $part     = 'get';
     private $code     = 200;
     private $errors   = [];
     private $success  = true;
     private $data     = [];
     private function getFailed(){
         return response([
             'module' => $this->module,
             'part'   => $this->part,
             'status' => 'failed',
             'errors' => $this->errors,
         ], 
         $this->code);
     }
     private function getSuccess(){
         return response([
             'module' => $this->module,
             'part'   => $this->part,
             'status' => 'ok',
             'data'   => $this->data,
         ], 
         $this->code
         );
     }
     public function setPart($part){
         $this->part = $part;
     }
     public function addError($error, $code=500){
        $this->success = false;
        $this->error[] = $error;
        $this->code    = $code;

     }
     public function addData($data){
        $this->data[] = $data;
     }
     public function get($data = false){
         if($data != false)
             $this->data = $data;
         if($this->success == false)
             return $this->getFailed();
         return $this->getSuccess();
     }
     public function check(){
         return $this->success;
     }
     function __construct($module='none', $part='http'){
         $this->module = $module;
         $this->part   = $part;
     }
}


