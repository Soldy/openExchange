<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\ExchangeHelper;

class exchangeGet extends Command
{
    use exchangeHelper;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchange:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'exchange ';

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
        $clist = \App\Exchange::TYPES;
        $this->info('currency exchange');
        $from = $this->choice(
            'Form currency',
            \App\Exchange::TYPES
        );
        $to = $this->choice(
             'Target currency',
            \App\Exchange::TYPES
        );
        $amount = $this->ask('amount');
         $result = $this->exchange($from, $to, $amount);
         if($result == false)
              $result = "something wrong";
        $this->line(
            $result
        );
        
    }
}
