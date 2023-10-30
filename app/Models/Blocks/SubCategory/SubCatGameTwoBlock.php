<?php

namespace App\Models\Blocks\SubCategory;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubCatGameTwoBlock extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'sub_cat_game_two_blocks';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['question', 'answer_data', 'category_id', 'photo_path_one', 'photo_path_two'];
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
    protected function answerData(): Attribute
    {
        return Attribute::make(
            set: function ($value, $attributes) {
                return json_encode($value);
            },
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setPhotoPathOneAttribute($value)
    {
        $attribute_name = "photo_path_one";
        $disk = "public";
        $destination_path = "sub_cat_game_two_photos";

        if (is_file($value)) {
            $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path, $fileName = null);
        } else {
            $this->attributes[$attribute_name] = $value; // uncomment if this is a translatable field
        }
    }

    public function setPhotoPathTwoAttribute($value)
    {
        $attribute_name = "photo_path_two";
        $disk = "public";
        $destination_path = "sub_cat_game_two_photos";

        if (is_file($value)) {
            $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path, $fileName = null);
        } else {
            $this->attributes[$attribute_name] = $value; // uncomment if this is a translatable field
        }
    }
}
