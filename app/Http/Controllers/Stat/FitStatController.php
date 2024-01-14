<?php
declare(strict_types=1);

namespace App\Http\Controllers\Stat;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class FitStatController extends Controller
{
    public function jog(): \Illuminate\Http\JsonResponse
    {
        $startYear = date('Y', strtotime('-3 year'));
        $endYear = date('Y', strtotime('0 year'));


        $xAxisData = [];
        $series = [];

        for ($m = 1; $m <= 12; $m++) {
            $xAxisData[] = date('M', strtotime(date(sprintf("Y-%s-d", $m))));
            for ($year = $startYear; $year <= $endYear; $year++) {
                if (!isset($series[$year])) {
                    $series[$year] = [
                        'name' => $year,
                        'data' => [],
                    ];
                }

                if ($year <= date('Y') && $m <= date('m')) {
                    $series[$year]['data'][$m] = 0;
                }
            }
        }
        $fields = ['event_year', 'event_month', DB::raw('sum(`distance`) as distance')];
        $dbData = DB::table('jog_record')
            ->where('event_year', '>=', $startYear)
            ->groupBy('event_year', 'event_month')
            ->orderBy('event_year', 'asc')
            ->orderBy('event_month', 'asc')
            ->select($fields)
            ->get();

        $dataList = $dbData->toArray();
        foreach ($dataList as $data) {
            $year = $data->event_year;
            $month = $data->event_month;
            $series[$year]['data'][$month] = $data->distance;
        }

        foreach ($series as $year => $item) {
            $series[$year]['data'] = array_values($item['data']);
        }


        $series = array_values($series);

        $result = [
            'code' => 200,
            'msg' => 'success',
            'data' => [
                'xAxis_data' => $xAxisData,
                'series' => $series,
            ]
        ];
        return response()->json($result);
    }

}
