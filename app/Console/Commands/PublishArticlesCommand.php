<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class PublishArticlesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:publish-articles-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish articles which publish_date set on today date';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Article::query()
            ->whereDate('publish_date', '<=', Carbon::today())
            ->whereNull('preview_for')
            ->update([
                'published' => true
            ]);
    }
}
