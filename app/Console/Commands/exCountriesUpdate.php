<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Extras\ExCountriesExtra;


class exCountriesUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ex:countries:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Countries Update';

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
        $this->line('Ex Countries update');
        $ex = new ExCountriesExtra();
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
