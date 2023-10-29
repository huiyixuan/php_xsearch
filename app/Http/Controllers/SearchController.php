<?php

namespace App\Http\Controllers;

use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\ElasticsearchException;
use Illuminate\Http\Request;
use zjkal\TimeHelper;

class SearchController extends Controller
{


    public function search(Request $request)
    {
        $start = microtime(true);
        $keyword = $request->query('s');
        $isRaw = $request->query('raw');
        $client = ClientBuilder::create()
            ->setBasicAuthentication('elastic', 'elastic2021')
            ->setHosts(['elasticsearch:9200'])
            ->build();
        $searchParams = [
            'index' => 'php_xsearch_mock',
            'body' => [
                'query' => [
                    'match' => [
                        'name' => $keyword,
//                        'content' => 'wb6ijhja24dmbj',
//                        'timestamp' => ['>', time() - 3600]
                    ],
//                    'range' =>  [
//                        'timestamp' => [
//                            'gte' => time() - 3600,
//                            'lte' => time(),
//                        ],
//                    ]
                ]
            ]
        ];
        try {

            $res = $client->search($searchParams)->asString();
        } catch (ElasticsearchException $e) {
            echo $e->getMessage();exit;
        }
        $raw = json_decode($res, true);
        $duration = round(microtime(true) - $start, 2);
        $list = [];


        array_map(function($v) use (&$list){
            $item = [
                'name' => $v['_source']['name'],
                'content' => $v['_source']['content'],
                'datetime' => TimeHelper::toFriendly($v['_source']['timestamp']),
            ];
            $list[] = $item;
            return $v;
        }, $raw['hits']['hits']);


        $result = [
            'keyword' => $keyword,
            'list' => $list,
            'duration' => $duration,
            'request_id' => 'php_xsearch' . uniqid(),
        ];
        if ($isRaw) {
            $result['raw'] = $raw;
        }
        return response()->json($result);
    }

    //
    public function demo(Request $request)
    {
        $query = $request->query();
        $post = $request->post();

        $result = [
            'query' => $query,
            'post' => $post,
        ];
        return response()->json($result);
    }
}
