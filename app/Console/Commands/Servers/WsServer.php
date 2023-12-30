<?php

namespace App\Console\Commands\Servers;

use App\Console\Commands\SwooleServer;
use Illuminate\Console\Command;

class WsServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ws';

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
        $host = '0.0.0.0';
        //创建WebSocket Server对象，监听0.0.0.0:9502端口。
//        $ws = new \Swoole\WebSocket\Server($host, $port, SWOOLE_PROCESS, SWOOLE_SOCK_TCP | SWOOLE_SSL);
//        $ws->set([
//            'ssl_cert_file' => __DIR__ . '/../../../../config/ssl/ssl.crt',
//            'ssl_key_file' => __DIR__ . '/../../../../config/ssl/ssl.key',
//        ]);
        $ws = new \Swoole\WebSocket\Server($host, $port);
        //监听WebSocket连接打开事件。
        $ws->on('Open', function ($ws, $request) {
            echo "client-{$request->fd} is connected\n";
            $ws->push($request->fd, "hello, welcome\n");
        });

        //监听WebSocket消息事件。
        $ws->on('Message', function (\Swoole\WebSocket\Server $ws, $frame) {
            echo "Message: client-{$frame->fd}:{$frame->data}\n";
//            $ws->push($frame->fd, "hello, I am sever, have you said: {$frame->data}");
            sleep(rand(2, 7));
            $ws->push($frame->fd, "enen");
        });

        //监听WebSocket连接关闭事件。
        $ws->on('Close', function ($ws, $fd) {
            echo "client-{$fd} is closed\n";
        });

        echo "running at port {$port}" . PHP_EOL;
        $ws->start();
    }
}
