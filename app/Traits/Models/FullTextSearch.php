<?php

namespace App\Traits\Models;


trait FullTextSearch
{
    /**
     * Replaces spaces with full text search wildcards
     *
     * @param string $term
     * @return string
     */
    protected function fullTextWildcards($term)
    {
        // removing symbols used by MySQL
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~', '!', '*', '\\', '', '{', '}'];

        $term = str_replace($reservedSymbols, '', $term);

        $words = explode(' ', $term);

        // foreach ($words as $key => $word) {
        //     if (strlen($word) >= 3) {
        //         $words[$key] = "{$word}*";
        //     }
        // }

        // dd(implode(' ', $words));
        return implode(' ', $words);

    }

    /**
     * Scope a query that matches a full text search of term.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $term
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $term)
    {
        $columns = collect($this->searchable)->map(function ($column) {
            return $this->qualifyColumn($column);
        })->implode(',');

        $query->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)", $this->fullTextWildcards($term))
        ->orWhere('title', 'LIKE', "%{$term}%");

        return $query;
    }
}
