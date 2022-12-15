<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    //buat fungsi untuk akses gambar dari public
    public function getImage($filename)
    {
        $path = public_path('images/' . $filename);
        if (!file_exists($path)) {
            return response()->json([
                'message' => 'File not found'
            ], 404);
        }
        $file = file_get_contents($path);
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $response = response($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }
}
