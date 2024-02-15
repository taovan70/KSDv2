<?php

namespace App\Services;

use App\Http\Requests\Article\ArticleStoreRequest;
use App\Models\Article;
use App\Models\Author;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Exception\CommonMarkException;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Exceptions\MediaCannotBeDeleted;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ArticleService
{

    public function __construct()
    {
    }

    public function getArticles(?string $search, ?string $status): Collection
    {
        $status = true; // $status === 'published';
        return Article::query()
            ->when(isset($search), fn (Builder $query) => $query->where('name', 'LIKE', "%{$search}%"))
            ->where('published', $status)
            ->where('preview_for', null)
            ->with('tags')
            ->orderBy('name')
            ->get();
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function getAuthorsByCategory(Request $request): Collection
    {
        $search = $request->input('q');
        $categoryField = Arr::first($request->form, function ($value) {
            return $value['name'] === 'category_id';
        });

        $authors = Author::query()
            ->when(isset($categoryField['value']), function (Builder $query) use ($categoryField) {
                $query->whereHas('categories', function (Builder $query) use ($categoryField) {
                    $query->where('category_id', $categoryField['value']);
                });
            })
            ->when(isset($search), function (Builder $query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('surname', 'LIKE', "%{$search}%")
                    ->orWhere('middle_name', 'LIKE', "%{$search}%");
            })
            ->get();

        if ($authors->isEmpty()) {
            $authors = Author::all();
        }

        return $authors->map(fn ($author) => [
            'id' => $author->id,
            'fullName' => $author->fullName
        ]);
    }

    /**
     * @param string $host
     * @param string $fileName
     * @return string
     */
    public function modifyUrl(string $host, string $fileName): string
    {
        $mediaData = DB::table('media')->where('file_name', $fileName)->first();
        //http://localhost/media/1/1684476639.jpg" format
        $folderMd5 = md5($mediaData->id);
        $folderDate = Carbon::parse($mediaData->created_at)->format('Y-m-d');
        return "{$host}/storage/{$mediaData->disk}/{$folderDate}/{$folderMd5}/{$mediaData->file_name}";
    }

    /**
     * @param Article $article
     * @param array $receivedUrls
     * @return void
     * @throws MediaCannotBeDeleted
     */
    public function deleteAttachedMedia(Article $article, array $receivedUrls): void
    {
        $mediaIds = DB::table('media')
            ->where([
                ['model_id', '=', $article->id],
                ['collection_name', '=', 'default']
            ])
            ->get();

        // if delete all
        if (!$receivedUrls) {
            foreach ($mediaIds as $mediaId) {
                $article->deleteMedia($mediaId->id);
            }
            return;
        }

        // if delete only those who was not received
        $mediaIdsNames = $mediaIds->pluck('file_name')->toArray();
        $receivedUrlsNames = [];
        foreach ($receivedUrls as $url) {
            $receivedUrlsNames[] = basename($url);
        }

        $differenceArray = array_diff($mediaIdsNames, $receivedUrlsNames);

        foreach ($differenceArray as $fileName) {
            $mediaToDelete = DB::table('media')->where('file_name', $fileName)->first();
            $article->deleteMedia($mediaToDelete->id);
        }
    }

    /**
     * @param ArticleStoreRequest $request
     * @param Article $article
     * @return string
     * @throws FileDoesNotExist
     * @throws FileIsTooBig|MediaCannotBeDeleted
     */
    public function convertImageUrls(ArticleStoreRequest $request, Article $article): string
    {
        $articleText = $request['content_markdown'];
        $host = request()->getSchemeAndHttpHost();
        $initialFilesPath = 'storage/temp_images/';
        $newMediaPath = '/media/';

        $pattern = '/\((https?:\/\/\S+)\)/';

        // Find all URLs in the article text
        preg_match_all($pattern, $articleText, $matches);

        // Extract all the URLs from the matches
        $allUrls = array_filter($matches[1], function ($v) {
            return $v !== '';
        });


        // Extract temp the URLs from the matches
        $tempUrls = array_filter($matches[1], function ($v) {
            return str_contains($v, 'temp_images');
        });

        $actualUrls = array_diff($allUrls, $tempUrls);

        if (!empty($actualUrls)) {
            $this->deleteAttachedMedia($article, $actualUrls);
        }

        foreach ($tempUrls as $url) {
            // if it is temporary file, alter them in actual posts files
            if (!str_contains($url, $newMediaPath)) {
                $fileName = basename($url);
                $article->addMedia(public_path($initialFilesPath . $fileName))->toMediaCollection();
                $modifiedUrl = $this->modifyUrl($host, $fileName);
                $articleText = str_replace("($url)", "($modifiedUrl)", $articleText);
            }
        }

        $re = '/!\[(?<altText>.*)\]\s*\((?<imageURL>.+)\)/m';
        preg_match_all($re, $articleText, $matches, PREG_SET_ORDER, 0);

        foreach ($matches as $eachMatch) {

            $fileName = basename($eachMatch['imageURL']);
            // save description in database
            $row = DB::table('media')->where('model_id', $article->id)->where('file_name', $fileName)->first();

            if (!empty($row)) {
                $mediaItem = Media::find($row->id);

                $mediaItem->setCustomProperty('description', $eachMatch['altText']); // adds a new custom property or updates an existing one
                $mediaItem->forgetCustomProperty('name'); // removes a custom property

                $mediaItem->save();
            }
        }

        return $articleText;
    }

    /**
     * @param string $text
     * @return string
     * @throws CommonMarkException
     */
    public function convertToHtml(string $text): string
    {
        $converter = new CommonMarkConverter();
        return $converter->convert($text);
    }

    public function updatePostsDate(int $days): void
    {
        $articles = Article::where('published', true)->get();
        foreach ($articles as $article) {
            $newCreatedAt = $article->publish_date->addDays($days);
            // Check if the new date is in the future
            if (!$newCreatedAt->isFuture()) {
                $article->publish_date = $newCreatedAt;
                $article->save();
            }
        }

    }

    public function checkIfArticleExistsInBlocks(int $id)
    {
        $message = '';
        $messageBlocks = '';
        $bitCardArticleExists = DB::table('big_card_articles')->where('article_id', $id)->exists();
        if ($bitCardArticleExists) {
            $messageBlocks .= '<br> "Статья в большой карточке(главная)"';
        }

        $everyoneTalkingAboutsOne = DB::table('everyone_talking_abouts')->where('article_one_id', $id)->exists();
        $everyoneTalkingAboutsTwo = DB::table('everyone_talking_abouts')->where('article_two_id', $id)->exists();
        $everyoneTalkingAboutsThree = DB::table('everyone_talking_abouts')->where('article_three_id', $id)->exists();
        $everyoneTalkingAboutsFour = DB::table('everyone_talking_abouts')->where('article_four_id', $id)->exists();
        $everyoneTalkingAboutsFive = DB::table('everyone_talking_abouts')->where('article_five_id', $id)->exists();
        $everyoneTalkingAboutsSix = DB::table('everyone_talking_abouts')->where('article_six_id', $id)->exists();
        if ($everyoneTalkingAboutsOne || $everyoneTalkingAboutsTwo || $everyoneTalkingAboutsThree || $everyoneTalkingAboutsFour || $everyoneTalkingAboutsFive || $everyoneTalkingAboutsSix) {
            $messageBlocks .= '<br> "О чём все говорят(категория)"';
        }

        $mostTalkedArticle = DB::table('most_talked_articles')->where('article_id', $id)->exists();
        if ($mostTalkedArticle) {
            $messageBlocks .= '<br> "Самые обсуждаемые статьи(главная)"';
        }

        $popularExpertArticles = DB::table('popular_expert_articles')->where('article_id', $id)->exists();
        if ($popularExpertArticles) {
            $messageBlocks .= '<br> "Популярные статьи экспертов(эксперт)"';
        }

        $popularNotFoundArticles = DB::table('popular_not_found_articles')->where('article_id', $id)->exists();
        if ($popularNotFoundArticles) {
            $messageBlocks .= '<br> "Самые популярные статьи(404)"';
        }

        $popularNotFoundTwoWeeksArticles = DB::table('popular_not_found_two_weeks_articles')->where('article_id', $id)->exists();
        if ($popularNotFoundTwoWeeksArticles) {
            $messageBlocks .= '<br> "Лучшее за две недели(404)"';
        }

        $QACategoriesOne = DB::table('q_a_categories')->where('article_one_id', $id)->exists();
        $QACategoriesTwo = DB::table('q_a_categories')->where('article_two_id', $id)->exists();
        $QACategoriesThree = DB::table('q_a_categories')->where('article_three_id', $id)->exists();
        $QACategoriesFour = DB::table('q_a_categories')->where('article_four_id', $id)->exists();
        $QACategoriesFive = DB::table('q_a_categories')->where('article_five_id', $id)->exists();
        $QACategoriesSix = DB::table('q_a_categories')->where('article_six_id', $id)->exists();

        if ($QACategoriesOne || $QACategoriesTwo || $QACategoriesThree || $QACategoriesFour || $QACategoriesFive || $QACategoriesSix) {
            $messageBlocks .= '<br>"Вопрос-Ответ(категория)"';
        }

        $readersRecomendArticles = DB::table('readers_recomend_articles')->where('article_id', $id)->exists();
        if ($readersRecomendArticles) {
            $messageBlocks .= '<br> "Читатели рекомендуют(главная)"';
        }

        $storyItems = DB::table('story_items')->where('article_id', $id)->exists();
        if ($storyItems) {
            $messageBlocks .= '<br> "Истории"';
        }

        $subCatBehindTheScenesBlocks = DB::table('sub_cat_behind_the_scenes_blocks')->where('article_id', $id)->exists();
        if ($subCatBehindTheScenesBlocks) {
            $messageBlocks .= '<br> "Заглянем за кулисы(подкатегория)"';
        }

        $subCatEncyclopediaBlocksOne = DB::table('sub_cat_encyclopedia_blocks')->where('article_one_id', $id)->exists();
        $subCatEncyclopediaBlocksTwo = DB::table('sub_cat_encyclopedia_blocks')->where('article_two_id', $id)->exists();
        $subCatEncyclopediaBlocksThree = DB::table('sub_cat_encyclopedia_blocks')->where('article_three_id', $id)->exists();
        $subCatEncyclopediaBlocksFour = DB::table('sub_cat_encyclopedia_blocks')->where('article_four_id', $id)->exists();
        if ($subCatEncyclopediaBlocksOne || $subCatEncyclopediaBlocksTwo || $subCatEncyclopediaBlocksThree || $subCatEncyclopediaBlocksFour) {
            $messageBlocks .= '<br> "Энциклопедия блок(подкатегория)"';
        }

        $subCatGameOneBlocks = DB::table('sub_cat_game_one_blocks')->where('article_id', $id)->exists();
        if ($subCatGameOneBlocks) {
            $messageBlocks .= '<br> "Игра первая(подкатегория)"';
        }

        $subCatGameTwoBlocks = DB::table('sub_cat_game_two_blocks')->where('article_id', $id)->exists();
        if ($subCatGameTwoBlocks) {
            $messageBlocks .= '<br> "Игра вторая(подкатегория)"';
        }

        $subCatTopFactsBlocksOne = DB::table('sub_cat_top_facts_blocks')->where('article_one_id', $id)->exists();
        $subCatTopFactsBlocksTwo = DB::table('sub_cat_top_facts_blocks')->where('article_two_id', $id)->exists();
        if ($subCatTopFactsBlocksOne || $subCatTopFactsBlocksTwo) {
            $messageBlocks .= '<br> "Верхний блок фактов(подкатегория)"';
        }

        if (!empty($messageBlocks)) {
            $message = 'Невозможно удалить стаью. Статью используют следующие блоки: ' . $messageBlocks;
        }

        return $message;

    }
}
