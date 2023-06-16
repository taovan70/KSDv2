<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function tempStore(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'upload' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048'
        ]);

        $dir = '/storage/temp_images';

        $image = $request->file('upload');
        $imageName = time().'.'.$image->extension();
        $imagePath = public_path() . $dir;

        $image->move($imagePath, $imageName);

        return response()->json(
            ["url"=>url($dir.'/'.$imageName)]
        );
    }
}
