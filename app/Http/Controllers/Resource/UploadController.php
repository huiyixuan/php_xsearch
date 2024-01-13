<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;

class UploadController extends Controller
{

    public function store()
    {
        $result = [
            'code' => 200,
            'msg' => 'success',
            'data' => [],
        ];

        if (request()->hasFile('file')) {
            $file = request()->file('file');

            $filename = $file->getClientOriginalName();
            $fileInfo = pathinfo($filename);

            $newFilename = $fileInfo['filename'] . '_' . uniqid() . '.' . $fileInfo['extension'];
            $res = move_uploaded_file($file->getPathname(), app()->basePath() . '/storage/uploads/' . $newFilename);
            if (!$res) {
                $result = [
                    'code' => 502,
                    'msg' => 'upload failed',
                    'data' => [],
                ];
                return response()->json($result);

            }
        }
        return response()->json($result);
    }
}
