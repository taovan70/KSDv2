<?php

namespace App\Models\Blocks\SubCategory;

use App\Models\Article;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubCatKnowMoreAboutEachBlock extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'sub_cat_know_more_about_each_blocks';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['name', 'block_data', 'category_id'];
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

    protected function blockData(): Attribute
    {
        return Attribute::make(
            set: function ($value, $attributes) {

                $attribute_name = "photo_path";
                $disk = "public";
                $destination_path = "sub_cat_know_more_photos";

                foreach ($value as $key => $valueItem) {
                    $file = !empty($valueItem["photo_path"]) ? $valueItem["photo_path"] : null;
                    if (is_file($file)) {
                        if($file->isValid()) {
                            // remove old file
                            if(!empty($this->attributes["block_data"])) {
                                $old_file_name = !empty(
                                json_decode(
                                    $this->attributes["block_data"]
                                )[$key]->{$attribute_name}
                                ) ? json_decode($this->attributes["block_data"])[$key]->{$attribute_name} : null;
                                if (!empty($this->attributes["block_data"]) && $old_file_name) {

                                    \Storage::disk($disk)->delete(
                                        json_decode($this->attributes["block_data"])[$key]->{$attribute_name}
                                    );
                                }
                            }
                            // create new file
                            $new_file_name = $fileName ?? md5($file->getClientOriginalName().random_int(1, 9999).time()).'.'.$file->getClientOriginalExtension();
                            $filePath = $file->storeAs($destination_path, $new_file_name, $disk);
                            $value[$key][$attribute_name] = $filePath;
                        }
                    }
                }

                return json_encode($value);
            },
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setPhotoPathAttribute($value)
    {
        $attribute_name = "photo_path";
        $disk = "public";
        $destination_path = "sub_cat_know_more_photos";

        if (is_file($value)) {
            $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path, $fileName = null);
        } else {
            $this->attributes[$attribute_name] = $value; // uncomment if this is a translatable field
        }

    }
}
