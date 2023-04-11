<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        self::VK => self::VK,
        self::INSTAGRAM => self::INSTAGRAM,
        self::FACEBOOK => self::FACEBOOK,
        self::TELEGRAM => self::TELEGRAM,
        self::TWITTER => self::TWITTER,
        self::REDDIT => self::REDDIT
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
//    protected $casts = [
//        'social_networks' => 'array'
//    ];

    // trigger events
    protected $dispatchesEvents = [
        'created' => \App\Events\LogUserActionOnModel::class,
        'updated' => \App\Events\LogUserActionOnModel::class,
        'deleted' => \App\Events\LogUserActionOnModel::class,
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

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
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

    protected function fullGender(): Attribute
    {
        return Attribute::make(
            get: function($value, $attributes) {
                return __("table.users.{$attributes['gender']}");
            }
        );
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: function($value, $attributes) {
                return "{$attributes['name']} {$attributes['middle_name']} {$attributes['surname']}";
            }
        );
    }

    protected function socialNetworksArray(): Attribute
    {
        return Attribute::make(
            get: function($value, $attributes) {
                $socialNetworks = [];
                foreach (json_decode($attributes['social_networks']) ?? [] as $socialNetwork) {
                    $socialNetworks[] = "{$socialNetwork->social_network}: {$socialNetwork->account}";
                }

                return $socialNetworks;
            }
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
        $destination_path = "authors_photos";

        if (is_file($value)) {
            $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path, $fileName = null);
        } else {
            $this->attributes[$attribute_name] = $value; // uncomment if this is a translatable field
        }

    }
}
