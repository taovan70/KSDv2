<?php

namespace App\Models\Blocks\SubCategory;

use App\Models\Article;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubCatEncyclopediaBlock extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'sub_cat_encyclopedia_blocks';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'category_id',
        'text',
        'photo_path',
        'article_one_id',
        'article_two_id',
        'article_three_id',
        'article_four_id'
    ];
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

    public function article_one(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function article_two(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function article_three(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function article_four(): BelongsTo
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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setPhotoPathAttribute($value)
    {
        $attribute_name = "photo_path";
        $disk = "public";
        $destination_path = "sub_cat_encyclopedia_photos";

        if (is_file($value)) {
            $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path, $fileName = null);
        } else {
            $this->attributes[$attribute_name] = $value; // uncomment if this is a translatable field
        }

    }
}
