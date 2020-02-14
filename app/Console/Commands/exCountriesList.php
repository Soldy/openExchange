<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class exCountriesList extends Command
{
    /**
     * The name and signature of the console command.
     *

     * @var string
     */
    protected $signature = 'ex:countries:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ex countries listes';

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
     * @return  void
     */
    public function handle()
    {
         $this->line('Country List');
         $headers = ['id', 'name', 'alpha2', 'alpha3'];
         $countries = \App\ExCountries::all(
             ['id', 'name', 'alpha2', 'alpha3']
         )->toArray();
         $this->table($headers, $countries);
    }
}
