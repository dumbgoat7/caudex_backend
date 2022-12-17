<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    //buat fungsi untuk akses pdf dari public
    public function getPdf($filename)
    {
        $path = public_path('pdf/'  . $filename);
        if (!file_exists($path)) {
            return response()->json([
                'message' => 'File not found'
            ], 404);
        }

        header('Content-type:application/pdf');
        header('Content-disposition: inline; filename="' . $filename . '"');
        header('content-Transfer-Encoding:binary');
        header('Accept-Ranges:bytes');
        @readfile($path);
        // @readfile($path);
    }
}
