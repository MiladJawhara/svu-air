<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class insertData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $statements = \File::get("database/SQLStatments/questions.sql");
        echo $statements;
        return 0;
    }
}
