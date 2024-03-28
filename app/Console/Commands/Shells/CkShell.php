<?php

namespace App\Console\Commands\Shells;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CkShell extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ck';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clickhouse操作';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $query = "SELECT * FROM test LIMIT 2";
        $result = DB::connection('clickhouse')->select($query);
        var_dump($result);

    }

}
