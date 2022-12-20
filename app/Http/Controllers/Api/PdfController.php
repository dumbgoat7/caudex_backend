<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reads;

class PdfController extends Controller
{
    //buat fungsi untuk akses pdf dari public
    public function getPdf($filename, $id, $idBuku)
    {
        $path = public_path('pdf/'  . $filename);
        if (!file_exists($path)) {
            return response()->json([
                'message' => 'File not found'
            ], 404);
        }

        $read = Reads::create([
            'read_user' => $id,
            'read_book' => $idBuku,
            'read_date' => date('Y-m-d H:i:s'),
        ]);
        $read->save();
        return response()->file($path);
    }
}
