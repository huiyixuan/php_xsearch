<?php

namespace App\Console\Commands\Tasks;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class ElasticMockTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ElasticMockTask';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'elastic数据测试';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $queue = 'elastic_test_queue';

        $limit = 50;
        $speakers = [
            'zhang',
            'xie',
            'zhao',
            'wang',
            'zheng',
            'xiao',
            'bai',
            'peng',
            'pei',
            'mao',
            'li',
            'liu',
            'qian',
            'sun',
            'shen',
            'lu',
        ];
        $chars = 'abcdefghijklmnbawwjoj40942312566';
        while (true) {
            $len = Redis::command('llen', [$queue]);
            if ($len < $limit) {
                $count = rand(1, 4);
                while($count--) {
                    $idx = rand(0, count($speakers) - 1);
                    $name = $speakers[$idx];
                    $chars = str_shuffle($chars);
                    $data = [
                        'name' => $name,
                        'content' => substr($chars, 0, 14),
                        'timestamp' => time(),
                        'datetime' => date('Y-m-d H:i:s'),
                    ];
                    $msg = json_encode($data, JSON_UNESCAPED_UNICODE);
                    Redis::command('rPush', [$queue, $msg]);
                }
                sleep(rand(10, 14));
            } else {
                sleep(10);
            }
        }
    }
}
