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
    protected $signature = 'ElasticShell';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'elastic脚本';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $client = ClientBuilder::create()
            ->setBasicAuthentication('elastic', 'elastic2021')
            ->setHosts(['elasticsearch:9200'])
            ->build();
        $searchParams = [
            'index' => 'php_xsearch_mock',
            'body' => [
                'query' => [
                    'match' => [
                        'name' => 'zheng'
                    ]
                ]
            ]
        ];
        $res = $client->search($searchParams);

        var_dump($res->asString());
        var_dump($res->getBody()->getSize());
    }
}
