<?php

namespace App\Services;

use App\Models\Author;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ArticleService
{
    public function getAuthorsBySubSection(Request $request)
    {
        $search          = $request->input('q');
        $subSectionField = Arr::first($request->form, function ($value) {
            return $value['name'] === 'sub_section_id';
        });

        return Author::query()
            ->when(isset($subSectionField['value']), function (Builder $query) use ($subSectionField) {
                $query->whereHas('subSections', function (Builder $query) use ($subSectionField) {
                    $query->where('sub_section_id', $subSectionField['value']);
                });
            })
            ->when(isset($search), function (Builder $query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('surname', 'LIKE', "%{$search}%")
                    ->orWhere('middle_name', 'LIKE', "%{$search}%");
            })
            ->get()
            ->map(fn($author) => [
                'id' => $author->id,
                'fullName' => $author->fullName
            ]);
    }

    public function parseArticle(string $articleText): void
    {
        dd($articleText);
    }
}