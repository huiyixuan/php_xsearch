<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class WsServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ws-server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $port = 10001;

        //创建WebSocket Server对象，监听0.0.0.0:9502端口。
        $ws = new \Swoole\WebSocket\Server('0.0.0.0', $port);

        //监听WebSocket连接打开事件。
        $ws->on('Open', function ($ws, $request) {
            $ws->push($request->fd, "hello, welcome\n");
        });

        //监听WebSocket消息事件。
        $ws->on('Message', function ($ws, $frame) {
            echo "Message: {$frame->data}\n";
            $ws->push($frame->fd, "server: {$frame->data}");
        });

        //监听WebSocket连接关闭事件。
        $ws->on('Close', function ($ws, $fd) {
            echo "client-{$fd} is closed\n";
        });

        echo "running at port {$port}" . PHP_EOL;
        $ws->start();
    }
}
