<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdvPage extends Model
{
    use HasFactory;

    public function advBlocks(): HasMany
    {
        return $this->hasMany(AdvBlock::class);
    }
}
