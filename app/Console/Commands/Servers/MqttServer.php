<?php

namespace App\Console\Commands\Servers;

use Elastic\Elasticsearch\Client;
use Illuminate\Console\Command;
use Simps\MQTT\Protocol\Types;
use Simps\MQTT\Protocol\V5;

class MqttServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mqtt-server';

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
        $port = 10005;
        $server = new \Swoole\Server('0.0.0.0', $port, SWOOLE_BASE);

        $server->set([
            'open_mqtt_protocol' => true, // 启用 MQTT 协议
            'worker_num' => 1,
        ]);

        $server->on('Connect', function ($server, $fd) {
            echo "Client:Connect.\n";
        });

        $server->on('Receive', function (\Swoole\Server $server, $fd, $reactor_id, $data) {
            $header = mqttGetHeader($data);

            if ($header['type'] == 1) {
                $resp = chr(32) . chr(2) . chr(0) . chr(0);
                eventConnect($header, substr($data, 2));
                $server->send($fd, $resp);
            } elseif ($header['type'] == 3) {
                $offset = 2;
                $topic = decodeString(substr($data, $offset));
                $offset += strlen($topic) + 2;
                $msg = substr($data, $offset);
                // 发布消息
                $msg = V5::pack([
                    'type' => Types::PUBLISH,
                    'topic' => 'test',
                    'message' => 'can you hear me, you just now say ' . $msg,
                ]);
                $server->send($fd, $msg);
            }
            echo "received length=" . strlen($data) . "\n";

        });

        $server->on('Close', function ($server, $fd) {
            echo "Client: Close.\n";
        });
        echo "start with port:{$port}" . PHP_EOL;

        $server->start();
    }
}
