<?php

namespace App\Console\Commands\Shells;

use Illuminate\Console\Command;
use Junges\Kafka\Commit\Contracts\CommitterFactory;
use Junges\Kafka\Commit\KafkaCommitter;
use Junges\Kafka\Contracts\KafkaConsumerMessage;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;
use Kafka\Consumer;
use Psr\Http\Message\MessageInterface;


class KafkaShell extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'k {arg}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'kafka生产者';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $keyword = $this->argument("arg");
        var_dump(strpos("p", $keyword));
        if (strpos("p", $keyword) === false) {
            $this->consume();

        } else {
            $this->produce();

        }
    }

    protected function produce()
    {

        $topic = "mykafka";
        Kafka::publishOn($topic)
            ->withBodyKey('foo', date("Y-m-d H:i:s"))
            ->withHeaders([
                'foo-header' => 'foo-value'
            ])
            ->send();
        echo "produce a message\n";
    }

    protected function consume()
    {

        // $topic = "mykafka";
        // $consumerGroupId = "test-consumer";
        // $consumer = Kafka::createConsumer()
        //     ->subscribe($topic)
        //     // ->withHandler(new TestHandler)
        //     ->withHandler(function (MessageInterface $message)  {
        //         echo json_encode($message, JSON_UNESCAPED_UNICODE);
        //
        //         $kafkaCommitter = new KafkaCommitter(app(Consumer::class, [
        //             'conf' => new Conf,
        //         ]));
        //
        //         $m = new Message();
        //         $kafkaCommitter->commitMessage(new Message(), true);
        //     })
        //     ->withAutoCommit(false)
        //     ->withConsumerGroupId($consumerGroupId)
        //     ->usingCommitterFactory(new CommitterFactory())
        //     ->build();
        //
        // echo "build finish \n";
        // $consumer->consume();



    }


}
