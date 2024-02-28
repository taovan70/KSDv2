<?php

namespace App\Http\Controllers\Api\Blocks;


use App\Http\Controllers\Controller;
use App\Http\Resources\api\Blocks\InfoBlockResource;
use App\Models\InfoBlock;

class InfoBlockController extends Controller
{

    public function show(string $id)
    {
        if (is_numeric($id)) {
            $infoBlock = InfoBlock::find($id);
        } else {
            $infoBlock = InfoBlock::where('slug', '=',  $id)->first();
        }

        if (empty($infoBlock)) {
            return [];
        }
        return new InfoBlockResource($infoBlock);
    }
}
