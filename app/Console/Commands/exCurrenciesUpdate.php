<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Extras\ExCurrenciesExtra;

class exCurrenciesUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ex:currencies:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Currencies Update';

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
        $this->line('Ex Currencies update');
        $ex = new ExCurrenciesExtra();
        $this->line('Get list form api ...');
        $result = $ex->updateFormApi();
        if($result === true){
            $this->line('updating database ...');
            $result = $ex->updateAll();
            $this->line('ready.');
        }elseif($result == false){
            $this->line('error');
        }else
            $this->line($result);
    }
}
