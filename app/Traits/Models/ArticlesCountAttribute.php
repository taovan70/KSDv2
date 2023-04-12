<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait ArticlesCountAttribute
{
    protected function articlesCount(): Attribute
    {
        return Attribute::make(
            get: function () {
                return self::articles()->count() . ' ' . __('models.articles');
            }
        );
    }
}