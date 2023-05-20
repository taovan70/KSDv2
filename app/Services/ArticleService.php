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

class ArticleService
{

    public function __construct()
    {
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

        return $authors->map(fn($author) => [
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
        $mediaIds = DB::table('media')->where('model_id', $article->id)->get();

        // if delete all
        if(!$receivedUrls) {
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

        // Extract the URLs from the matches
        $urls = $matches[1];

        $this->deleteAttachedMedia($article, $urls);

        foreach ($urls as $url) {
            // if it is temporary file, and not new media file
            if (!str_contains($url, $newMediaPath)) {
                $fileName = basename($url);
                $article->addMedia(public_path($initialFilesPath . $fileName))->toMediaCollection();
                $modifiedUrl = $this->modifyUrl($host, $fileName);
                $articleText = str_replace("($url)", "($modifiedUrl)", $articleText);
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

}
