<?php

namespace App\Handlers;

use Junges\Kafka\Contracts\KafkaConsumerMessage;

class TestHandler
{
    public function __invoke(KafkaConsumerMessage $message)
    {
        $a  = [
            'body' => $message->getBody(),
            'headers' => $message->getHeaders(),
            'partition' => $message->getPartition(),
            'key' => $message->getKey(),
            'topic' => $message->getTopicNameon
            ()
        ];
        $a = json_encode($a, JSON_UNESCAPED_UNICODE) . "\n";
        echo $a;
        // file_put_contents("a.log", $a, 8 );
    }
}
