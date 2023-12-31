<?php

namespace App\Console\Commands\Shells;

use Illuminate\Console\Command;
use Elastic\Elasticsearch\ClientBuilder;


class ElasticShell extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'es';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'elastic脚本';

    protected $client;

    public function getClient()
    {
        if (empty($this->client)) {
            $this->client = ClientBuilder::create()
                ->setBasicAuthentication('elastic', 'elastic2021')
                ->setHosts(['elasticsearch:9200'])
                ->build();

        }
        return $this->client;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $index = 'poetry';
        // $this->deleteIndex($index);
        $this->createIndex($index);
        // $this->putMapping($index);
        $this->insertDoc($index);
        // $this->searchDoc($index);
        // $this->getIndex($index);
    }

    public function searchDoc($index)
    {
        $params = [
            'index' => $index,
        ];
        $res = $this->getClient()->search($params);
        $data = json_decode($res->getBody()->getContents(), true);
        var_dump($data);
    }

    protected function insertDoc($index)
    {


        $json = file_get_contents('poetry.json');
        $dataList = json_decode($json, true);
        foreach ($dataList as $data) {
            $params = [
                'index' => $index,
                'body' => [
                    'properties' => $data
                ],
            ];
            $res = $this->getClient()->index($params);
            var_dump($res->getBody()->getContents());
        }

    }

    protected function putMapping($index)
    {
        $params = [
            'index' => $index,
            'body' => [
                'properties' => [
                    'title' => ['type' => 'text'],
                    'author' => ['type' => 'text'],
                    'dynasty' => ['type' => 'keyword'],
                    'poetry' => ['type' => 'text'],
                ]
            ],
        ];
        $res = $this->getClient()->indices()->putMapping($params);
        var_dump($res->getBody()->getContents());
    }

    protected function getIndex($index)
    {
        // 删除索引
        $params = [
            'index' => $index,
        ];
        $res = $this->getClient()->indices()->get($params);
        var_dump($res->getBody()->getContents());
    }


    protected function deleteIndex($index)
    {
        // 删除索引
        $params = [
            'index' => $index,
        ];
        $res = $this->getClient()->indices()->delete($params);
        var_dump($res->getBody()->getContents());
    }

    // 创建索引
    protected function createIndex($index)
    {
        // 创建索引
        $params = [
            'index' => $index,
            'body' => [
                'settings' => [
                    'number_of_shards' => 1,
                    'number_of_replicas' => 0,
                ],
                'mappings' => [
                    'properties' => [
                        'dynasty' => ['type' => 'keyword'],
                        'title' => ['type' => 'text'],
                        'author' => ['type' => 'text'],
                        'poetry' => ['type' => 'text'],
                    ]
                ]
            ]
        ];
        $res = $this->getClient()->indices()->create($params);
        var_dump($res->getBody()->getContents());
    }
}
