<?php

namespace App\Providers;

use App\Events\LogUserActionOnModel;
use App\Listeners\SendLogUserActionOnAuth;
use App\Listeners\SendLogUserActionOnModel;
use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\CustomSetting;
use App\Models\Tag;
use App\Models\User;
use App\Observers\ArticleObserver;
use App\Observers\AuthorObserver;
use App\Observers\CategoryObserver;
use App\Observers\CustomSettingObserver;
use App\Observers\TagObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Login::class => [
            SendLogUserActionOnAuth::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Event::listen(
            LogUserActionOnModel::class,
            [SendLogUserActionOnModel::class, 'handle']
        );

        Category::observe(CategoryObserver::class);
        Article::observe(ArticleObserver::class);
        Author::observe(AuthorObserver::class);
        CustomSetting::observe(CustomSettingObserver::class);
        Tag::observe(TagObserver::class);
        User::observe(UserObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
