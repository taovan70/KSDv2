<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class FileController extends Controller
{
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $file = $request->file('upload');
        $imageName = time().'.'.$file->extension();
        $imagePath = public_path(). '/files';

        $file->move($imagePath, $imageName);

        return response()->json(
            ["url"=>url('/files/'.$imageName)]
        );
    }
}
