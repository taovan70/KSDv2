<?php

namespace App\Jobs\Article;

use App\Models\Article;
use App\Services\ArticleElementService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveArticleImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Article $article;

    /**
     * Create a new job instance.
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /** @var ArticleElementService $articleElementService */
        $articleElementService = app(ArticleElementService::class);

        $articleElementService->saveImages($this->article);
    }
}
