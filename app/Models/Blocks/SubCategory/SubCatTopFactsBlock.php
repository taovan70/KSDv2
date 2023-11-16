<?php

namespace App\Models\Blocks\SubCategory;

use App\Models\Article;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubCatTopFactsBlock extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'sub_cat_top_facts_blocks';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
     protected $fillable = [
         'name',
         'category_id',
         'text',
         'number_one',
         'text_one',
         'number_two',
         'text_two',
         'number_three',
         'text_three',
         'article_one_id',
         'article_two_id',
         'background_photo_path'
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

    public function article_one(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function article_two(): BelongsTo
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

    public function setBackgroundPhotoPathAttribute($value)
    {
        $attribute_name = "background_photo_path";
        $disk = "public";
        $destination_path = "sub_cat_top_facts_blocks_background_photos";

        if (is_file($value)) {
            $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path, $fileName = null);
        } else {
            $this->attributes[$attribute_name] = $value; // uncomment if this is a translatable field
        }

    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
