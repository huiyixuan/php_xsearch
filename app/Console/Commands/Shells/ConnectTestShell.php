<?php

namespace App\Console\Commands\Shells;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class ConnectTestShell extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ConnectTestShell';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '服务连接测试';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->testRedis();

    }

    protected function testRedis()
    {
        $res = 'available';
        $config = config('database.redis');
        $res = Redis::command('set', ['key', 'val']);
        var_dump($res);
        exit;
        echo "redis server " . $res . PHP_EOL;
    }
}
