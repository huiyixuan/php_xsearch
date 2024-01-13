<?php

namespace App\Common\Utils;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class PhpExcel
{

    public function getSheetContent($filename)
    {
        if (!file_exists($filename)) {
            return [false, '文件不存在'];
        }
        $xlsx = new Xlsx();
        $x = $xlsx->load($filename);
        $sheets = $x->getAllSheets();
        $dataList = [];
        $time = time();
        foreach ($sheets as $sheet) {

            $title = $sheet->getTitle();
            // var_dump($title);
            if (false !== mb_strpos($title, '跑步日志')) {
                $rowNum = $sheet->getHighestRow();
                echo $title . ':' . $rowNum . PHP_EOL;

                for ($r = 3; $r <= $rowNum; $r++) {
                    $date = strval($sheet->getCell('A'.$r)->getValue());
                    if (empty($date)) {
                        break;
                    }

                    $distance = $sheet->getCell('B'.$r)->getValue();
                    $duration = $sheet->getCell('c'.$r)->getValue();
                    if (empty($distance)) {
                        continue;
                    }

                    $sec = 0;
                    if (str_contains($duration, '.')) {
                        $min = substr($duration, 0, strpos($duration, '.'));
                        $sec = substr($duration, strpos($duration, '.') + 1);
                        $sec = str_pad($sec, STR_PAD_RIGHT, 0);
                    } else {
                        $min = $duration;
                    }

                    $seconds = $min * 60 + intval($sec);

                    $date = Date::excelToDateTimeObject($date)->format("Y-m-d");
                    $data = [
                        'event_year' => substr($date, 0, 4),
                        'event_month' => substr($date, 5, 2),
                        'event_day' => substr($date, 8),
                        'distance' => number_format($distance, 1),
                        'duration' => $seconds,
                        'add_time' => $time,
                    ];

                    $dataList[] = $data;
                }
            }
        }

        return $dataList;


    }

}
