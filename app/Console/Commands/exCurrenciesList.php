<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class exCurrenciesList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ex:currencies:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Currencies List';

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
         $this->line('ex Currencies List');
         $headers = ['id', 'name', 'code', 'symbol', 'enabled'];
         $currencies = \App\ExCurrencies::all(
             ['id', 'name', 'code', 'symbol', 'enabled']
         )->toArray();
         $this->table($headers, $currencies);
    }
}
