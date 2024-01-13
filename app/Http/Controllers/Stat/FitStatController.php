<?php
declare(strict_types=1);

namespace App\Http\Controllers\Stat;

use App\Http\Controllers\Controller;

class FitStatController extends Controller
{
    public function jog(): \Illuminate\Http\JsonResponse
    {

        $xAxisData = [];
        $series = [];
        $startYear = 2020;
        $endYear = 2024;
        for ($m = 1; $m <= 12; $m++) {
            $xAxisData[] = date('M', strtotime(date(sprintf("Y-%s-d", $m))));

            for ($year = $startYear;$year<=$endYear; $year++) {
                if (!isset($series[$year])) {
                    $series[$year] = [
                        'name' => $year,
                        'data' => [],
                    ];
                }
                $series[$year]['data'][] = rand(100, 999);
            }

        }
        $series = array_values($series);

        $result = [
            'code' => 200,
            'msg' => 'success',
            'data' => [
                'xAxis_data' => $xAxisData,
                'series' => $series,
                    // [
                    // [
                    //     'name' => '2001',
                    //     'data' => [rand(100, 999), rand(100, 999), rand(100, 999), rand(100, 999)],
                    // ],
                    // [
                    //     'name' => '2002',
                    //     'data' => [rand(100, 999), rand(100, 999), rand(100, 999), rand(100, 999)],
                    // ],
                    // [
                    //     'name' => '2003',
                    //     'data' => [rand(100, 999), rand(100, 999), rand(100, 999), rand(100, 999)],
                    // ],
                    // [
                    //     'name' => '2004',
                    //     'data' => [rand(100, 999), rand(100, 999), rand(100, 999), rand(100, 999)],
                    // ],
                // ]
            ]
        ];
        return response()->json($result);
    }

}
