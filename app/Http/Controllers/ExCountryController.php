<?php

namespace App\Http\Controllers;

use App\ExCountries;
use App\Extras\OutputHelper;
use Illuminate\Http\Request;

class ExCountryController extends Controller
{
    private $output;
    public function list(){
        $this->output->setPart('list');
        return $this->output->get(
             $countries = \App\ExCountries::all(
                 ['id', 'name', 'alpha2', 'alpha3']
             )->toArray()
         );
    }
    function __construct(){
        $this->output   = new OutputHelper('country');
     }
}
