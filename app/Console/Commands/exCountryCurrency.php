<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class exCountryCurrency extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ex:CountryCurrency';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Country To Currency List';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
         $this->line('Country to Currency list');
         $headers = ['id', 'country', 'currency'];
         $list = \App\ExCountryCurrency::with(['country','currency'])
              ->get();
         $cclist = [];
         foreach($list as &$v){
             $cclist[]=[
                 "id"       =>$v['id'],
                 "country"  =>$v['country']['name'],
                 "currency" =>$v['currency']['name'],
             ];

         }
         $this->table($headers, $cclist);
    }
}
