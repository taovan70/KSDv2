<?php

namespace App\Models\Blocks\SubCategory;

use App\Models\Article;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubCatCalendar extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'sub_cat_calendars';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['name', 'month_data', 'category_id'];
    // protected $hidden = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Category::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    protected function monthData(): Attribute
    {
        return Attribute::make(
            set: function ($value, $attributes) {
                return json_encode($value);
            },
        );
    }

    // protected function monthDataArray(): Attribute
    // {
    //     return Attribute::make(
    //         get: function ($value, $attributes) {
    //             //dd($attributes);
    //             $monthDataResult = [];
    //             foreach (json_decode($attributes['month_data']) ?? [] as $monthData) {
    //                 $monthDataResult[] = "{$monthData->name}: {$monthData->text}";
    //             }

    //             return $monthDataResult;
    //         },
    //     );
    // }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
