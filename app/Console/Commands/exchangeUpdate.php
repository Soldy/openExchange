<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\ExchangeHelper;

class exchangeUpdate extends Command
{
    use exchangeHelper;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchange:Update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update exchange';

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

        $this->line(
            $this->update()
        );


    }
}
