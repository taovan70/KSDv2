<?php

namespace App\Models\Blocks\MainPage;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PopularCategories extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'popular_categories';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'category_id',
        'lft',
        'rgt',
        'depth',
        'parent_id',
        'photo_path',
    ];
    // protected $hidden = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            if (empty($item->name)) {
                $item->name = $item->category->name ?? 'У категории нет названия';
            }
        });
    }

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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setPhotoPathAttribute($value)
    {
        $attribute_name = "photo_path";
        $disk = "public";
        $destination_path = "popular_categories_main_page_photos";

        if (is_file($value)) {
            $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path, $fileName = null);
        } else {
            $this->attributes[$attribute_name] = $value; // uncomment if this is a translatable field
        }

    }
}
