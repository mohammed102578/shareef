<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Date_log;
class Date_log_add extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'date_log:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'add date log in tabels date_logs every day';

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
     * @return int
     */
    public function handle()
    {
       
            Date_log::create(['date'=>date('y-m-d')]);
       

}
}
