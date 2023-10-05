<?php

namespace App\Models;

use App\Helpers\DOMParser\DOMTags;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Article extends Model implements HasMedia
{
    use CrudTrait;
    use HasFactory;
    use InteractsWithMedia;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'articles';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'structure',
        'author_id',
        'category_id',
        'published',
        'publish_date',
        'content_markdown',
        'keywords',
        'description',
        'title',
        'slug',
        'preview_for'
    ];
    // protected $hidden = [];
    // protected $dates = [];
    protected $casts = [
        'published' => 'boolean',
        'publish_date' => 'datetime'
    ];
    protected $appends = ['tags_ids'];

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

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function headers(): HasMany
    {
        return $this->elements()->whereIn('html_tag', DOMTags::HEADERS);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
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

    protected function imagesStoragePath(): Attribute
    {
        return Attribute::make(
            get: fn() => "articles/{$this->id}/images/"
        );
    }

    public function getTagsIdsAttribute()
    {
        return $this->tags->pluck('id')->toArray();
    }

    public static function last()
    {
        return static::all()->last();
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */


}
