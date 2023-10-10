<?php

namespace App\Http\Controllers\Api\Blocks;


use App\Models\InfoBlock;
use App\Http\Controllers\Controller;
use App\Http\Resources\api\Blocks\InfoBlockResource;

class InfoBlockController extends Controller
{

    public function show(string $id)
    {
        $infoBlock = InfoBlock::findOrFail($id);
        return new InfoBlockResource($infoBlock);
    }
}
