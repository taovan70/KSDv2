<?php

namespace App\Models;

use App\Models\Blocks\MainPage\PopularCategories;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'categories';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['name', 'slug', 'parent_id', 'depth', 'lft', 'rgt', 'photo_path', 'icon_path', 'mini_pic_path', 'description', 'menu_order'];
    protected $hidden = ['created_at', 'updated_at'];
    // protected $dates = [];


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

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function subcategories(): HasMany
    {
        return $this->children()->with('subcategories');
    }

    public function parents(): BelongsTo
    {
        return $this->parent()->with('parents');
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function popularCategories(): HasMany
    {
        return $this->hasMany(PopularCategories::class);
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
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
        $destination_path = "categories_photos";

        if (is_file($value)) {
            $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path, $fileName = null);
        } else {
            $this->attributes[$attribute_name] = $value; // uncomment if this is a translatable field
        }
    }

    public function setMiniPicPathAttribute($value)
    {
        $attribute_name = "mini_pic_path";
        $disk = "public";
        $destination_path = "categories_mini_pics";

        if (is_file($value)) {
            $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path, $fileName = null);
        } else {
            $this->attributes[$attribute_name] = $value; // uncomment if this is a translatable field
        }
    }

    public function setIconPathAttribute($value)
    {
        $attribute_name = "icon_path";
        $disk = "public";
        $destination_path = "categories_icons";

        if (is_file($value)) {
            $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path, $fileName = null);
        } else {
            $this->attributes[$attribute_name] = $value; // uncomment if this is a translatable field
        }
    }
}
