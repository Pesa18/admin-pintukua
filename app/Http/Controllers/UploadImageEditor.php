<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadImageEditor extends Controller
{



    public function upload(Request $request)
    {

        if ($request->hasFile('upload')) {
            // Simpan file gambar
            $file = $request->file('upload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads', $filename, 'public'); // simpan di public storage

            // Kirim respon ke CKEditor
            $url = Storage::url($path);
            return response()->json([
                'url' => $url
            ]);
        }

        return response()->json(['error' => 'No file uploaded.'], 400);
    }
}
