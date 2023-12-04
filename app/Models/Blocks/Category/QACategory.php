<?php

namespace App\Models\Blocks\Category;


use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QACategory extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'q_a_categories';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable =  ['name', 'category_id', 'article_one_id', 'article_two_id', 'article_three_id', 'article_four_id', 'article_five_id', 'article_six_id'];
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

    public function article_one(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Article::class);
    }

    public function article_two(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Article::class);
    }
    public function article_three(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Article::class);
    }
    public function article_four(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Article::class);
    }
    public function article_five(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Article::class);
    }
    public function article_six(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Article::class);
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
}
