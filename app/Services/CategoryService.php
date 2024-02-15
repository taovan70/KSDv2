<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    /**
     * @param string|null $search
     * @return Collection
     */
    public function getCategories(?string $search): Collection
    {
        return Category::query()
            ->when(isset($search), fn(Builder $query) => $query->where('name', 'LIKE', "%{$search}%"))
            ->orderBy('name')
            ->get();
    }

    public function getCategoriesIds($category): ?array
    {
        if (!empty($category)) {
            $array = array($category->id);
            if (count($category->subcategories) == 0) {
                return $array;
            } else {
                return array_merge($array, $this->getChildrenIds($category->subcategories));
            }
        } else {
            return null;
        }
    }

    public function getChildrenIds($subcategories): array
    {
        $array = array();
        foreach ($subcategories as $subcategory) {
            array_push($array, $subcategory->id);
            if (count($subcategory->subcategories)) {
                $array = array_merge($array, $this->getChildrenIds($subcategory->subcategories));
            }
        }
        return $array;
    }

    public function getSubCategoryInfo($data, $crud) {
        $data['entries'] = $crud->getEntries();
        $data['crud'] = $crud;
        $data['title'] = $crud->getTitle() ?? trans(
            'backpack::crud.reorder'
        ) . ' ' . $crud->entity_name;
        $data['relations']['articles'] = Category::withCount('articles')->get();
        $data['relations']['categories'] = [];
        $allCategories = Category::all();
        foreach ($allCategories as $category) {
            $data['relations']['categories'][$category->name]['categories-1-level'] = $category->children()->count();

            $data['relations']['categories'][$category->name]['categories-2-level'] = $category->children()
                ->withCount('children')
                ->get()
                ->pluck('children_count')
                ->sum();

            $data['relations']['categories'][$category->name]['categories-3-level'] =  $category->children()
                ->with(['children' => function ($query) {
                    $query->withCount('children');
                }])
                ->get()
                ->pluck('children.*.children_count')
                ->flatten()
                ->sum();
        }

        return $data;
    }

    public function checkIfCategoryExistsInBlocks(int $id)
    {
        $message = '';
        $messageBlocks = '';

        $didYouKnowInArticles = DB::table('did_you_know_in_articles')->where('category_id', $id)->exists();
        if ($didYouKnowInArticles) {
            $messageBlocks .= '<br> " Знали ли вы(статья)"';
        }

        $everyoneTalkingAbouts = DB::table('everyone_talking_abouts')->where('category_id', $id)->exists();
        if ($everyoneTalkingAbouts) {
            $messageBlocks .= '<br> "О чём все говорят(категория)"';
        }

        $popularCategories = DB::table('popular_categories')->where('category_id', $id)->exists();
        if ($popularCategories) {
            $messageBlocks .= '<br> "Популярные рубрики(главная)"';
        }

        $popularNotFoundCategories = DB::table('popular_not_found_categories')->where('category_id', $id)->exists();
        if ($popularNotFoundCategories) {
            $messageBlocks .= '<br> "Часто читаемые рубрики(404)"';
        }

        $QACategories = DB::table('q_a_categories')->where('category_id', $id)->exists();
        if ($QACategories) {
            $messageBlocks .= '<br>"Вопрос-Ответ(категория)"';
        }

        $stories = DB::table('stories')->where('category_id', $id)->exists();
        if ($stories) {
            $messageBlocks .= '<br>"Истории"';
        }

        $subCatAlphaviteBlocks = DB::table('sub_cat_alphavite_blocks')->where('category_id', $id)->exists();
        if ($subCatAlphaviteBlocks) {
            $messageBlocks .= '<br>"Блок алфавита(подкатегория)"';
        }

        $subCatBehindTheScenesBlocks = DB::table('sub_cat_behind_the_scenes_blocks')->where('category_id', $id)->exists();
        if ($subCatBehindTheScenesBlocks) {
            $messageBlocks .= '<br> "Заглянем за кулисы(подкатегория)"';
        }

        $subCatCalendars = DB::table('sub_cat_calendars')->where('category_id', $id)->exists();
        if ($subCatCalendars) {
            $messageBlocks .= '<br> "Календарь(подкатегория)"';
        }

        $subCatEncyclopediaBlocks = DB::table('sub_cat_encyclopedia_blocks')->where('category_id', $id)->exists();
        if ($subCatEncyclopediaBlocks) {
            $messageBlocks .= '<br> "Энциклопедия блок(подкатегория)"';
        }

        $subcatExpertAdviceBlocks = DB::table('sub_cat_expert_advice_blocks')->where('category_id', $id)->exists();
        if ($subcatExpertAdviceBlocks) {
            $messageBlocks .= '<br> "Совет эксперта(подкатегория)"';
        }

        $subCatGameOneBlocks = DB::table('sub_cat_game_one_blocks')->where('category_id', $id)->exists();
        if ($subCatGameOneBlocks) {
            $messageBlocks .= '<br> "Игра первая(подкатегория)"';
        }

        $subCatGameTwoBlocks = DB::table('sub_cat_game_two_blocks')->where('category_id', $id)->exists();
        if ($subCatGameTwoBlocks) {
            $messageBlocks .= '<br> "Игра вторая(подкатегория)"';
        }

        $subCatInterestingBlocks = DB::table('sub_cat_interesting_blocks')->where('category_id', $id)->exists();
        if ($subCatInterestingBlocks) {
            $messageBlocks .= '<br> "Это интересно(подкатегория)"';
        }

        $subCatKnowMoreAboutEachBlocks = DB::table('sub_cat_know_more_about_each_blocks')->where('category_id', $id)->exists();
        if ($subCatKnowMoreAboutEachBlocks) {
            $messageBlocks .= '<br> " Узнать больше о каждом разделе(подкатегория)"';
        }

        $subCatTopFactsBlocks = DB::table('sub_cat_top_facts_blocks')->where('category_id', $id)->exists();
        if ($subCatTopFactsBlocks) {
            $messageBlocks .= '<br> "Верхний блок фактов(подкатегория)"';
        }

        if (!empty($messageBlocks)) {
            $message = '<b>Невозможно удалить категорию</b>. <br> Категорию используют следующие блоки: ' . $messageBlocks;
        }

        return $message;
    }
}
