<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Author extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    public const MALE = 'M';
    public const FEMALE = 'F';
    public const GENDERS = [
        self::MALE,
        self::FEMALE
    ];

    public const INSTAGRAM = 'instagram';
    public const TELEGRAM = 'telegram';
    public const VK = 'vk';
    public const TWITTER = 'twitter';
    public const REDDIT = 'reddit';
    public const FACEBOOK = 'facebook';
    public const SOCIAL_NETWORKS = [
        self::VK,
        self::INSTAGRAM,
        self::FACEBOOK,
        self::TELEGRAM,
        self::TWITTER,
        self::REDDIT
    ];

    protected $table = 'authors';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'surname',
        'middle_name',
        'age',
        'gender',
        'biography',
        'address',
        'photo_path',
        'personal_site',
        'social_networks'
    ];
    // protected $hidden = [];
    // protected $dates = [];
    protected $casts = [
        'social_networks' => 'array'
    ];

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

    public function subSections(): BelongsToMany
    {
        return $this->belongsToMany(SubSection::class);
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
        $destination_path = "authors_photos";

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path, $fileName = null);

        // return $this->attributes[{$attribute_name}]; // uncomment if this is a translatable field
    }
}
