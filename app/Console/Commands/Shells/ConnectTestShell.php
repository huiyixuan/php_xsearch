<?php

namespace App\Console\Commands\Shells;

use App\Common\Utils\PhpExcel;
use App\Models\JogRecordModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class ConnectTestShell extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 't';

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
        $phpExcel = new PhpExcel();
        $dir = app()->basePath() . '/storage/uploads/';
        $files = scandir($dir);
        foreach ($files as $file) {
            if (in_array($file, ['.','..'])) {
                continue;
            }

            if (false !== mb_strpos($file, '运动')){
                $filename = $dir . $file;
                $dataList = $phpExcel->getSheetContent($filename);
                JogRecordModel::insert($dataList);
            }

        }
    }
}
