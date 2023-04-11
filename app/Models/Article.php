<?php

namespace App\Models;

use App\Helpers\DOMParser\DOMTags;
use App\Services\ArticleService;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    use CrudTrait;
    use HasFactory;

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
        'content',
        'structure',
        'author_id',
        'sub_section_id'
    ];
    // protected $hidden = [];
    // protected $dates = [];
    protected $casts = [
        'content' => 'array',
        'structure' => 'array'
    ];

    protected $with = [
        'elements'
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

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function subSection(): BelongsTo
    {
        return $this->belongsTo(SubSection::class);
    }

    public function elements(): HasMany
    {
        return $this->hasMany(ArticleElement::class)->orderBy('order');
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

    protected function content(): Attribute
    {
        return Attribute::make(
            get: function() {
                /** @var ArticleService $articleService */
                $articleService = app(ArticleService::class);

                return $articleService->createArticleContent($this->elements);
            }
        );
    }

    protected function structure(): Attribute
    {
        return Attribute::make(
            get: function() {
                /** @var ArticleService $articleService */
                $articleService = app(ArticleService::class);

                return $articleService->createArticleStructure($this->headers);
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
