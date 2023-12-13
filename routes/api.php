<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([], function () {
    Route::post('article_authors', [\App\Http\Controllers\Api\ArticleController::class, 'fetchAuthors']);

    Route::group(['prefix' => 'tags'], function () {
        Route::post('/', [\App\Http\Controllers\Api\TagController::class, 'fetchTags']);
        Route::get('/', [\App\Http\Controllers\Api\TagController::class, 'index'])->name('tags.index');
    });

    Route::group(['prefix' => 'search'], function () {
        Route::post('/', [\App\Http\Controllers\Api\SearchController::class, 'search']);
    });

    Route::group(['prefix' => 'adv-pages'], function () {
        Route::get('/', [\App\Http\Controllers\Api\AdvPageController::class, 'fetchTags'])->name('advPage.index');
        Route::get('/{slug}', [\App\Http\Controllers\Api\AdvPageController::class, 'show'])->name('advPage.show');
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::post('/', [\App\Http\Controllers\Api\CategoryController::class, 'fetchCategories'])->name('categoriesPost.index');
        Route::get('/', [\App\Http\Controllers\Api\CategoryController::class, 'fetchCategoriesAsTree'])->name('categories.index');
        Route::get('/{slug}', [\App\Http\Controllers\Api\CategoryController::class, 'show'])->name('categories.show');
    });

    Route::group(['prefix' => 'articles'], function () {
        Route::post('/', [\App\Http\Controllers\Api\ArticleController::class, 'fetchArticles'])->name('articlePost.index');
        Route::get('/', [\App\Http\Controllers\Api\ArticleController::class, 'index'])->name('article.index');
        Route::get('/recent/pagination', [\App\Http\Controllers\Api\ArticleController::class, 'recentPagination'])->name('article.show-recent-pagination');
        Route::get('/random/{count}', [\App\Http\Controllers\Api\ArticleController::class, 'random'])->name('article.random');
        Route::get('/recent/{count}', [\App\Http\Controllers\Api\ArticleController::class, 'recent'])->name('article.show-recent');
        Route::get('/{slug}', [\App\Http\Controllers\Api\ArticleController::class, 'show'])->name('article.show-slug');
        Route::get('/id/{id}/{key}', [\App\Http\Controllers\Api\ArticleController::class, 'showPreview'])->name('article.show-id');
    });

    Route::group(['prefix' => 'authors'], function () {
        Route::post('/', [\App\Http\Controllers\Api\AuthorController::class, 'fetchAuthors'])->name('authorsPost.index');
        Route::get('/', [\App\Http\Controllers\Api\AuthorController::class, 'index'])->name('authorsGet.index');
        Route::get('/{id}', [\App\Http\Controllers\Api\AuthorController::class, 'show'])->name('authors.show');
    });

    Route::group(['prefix' => 'blocks'], function () {
        Route::group(['prefix' => 'authors'], function () {
            Route::group(['prefix' => 'popular-expert-articles'], function () {
                Route::get('/', [\App\Http\Controllers\Api\Blocks\Authors\PopularExpertArticlesController::class, 'index'])->name('popularExpertArticle.index');
            });
        });
        Route::group(['prefix' => 'article'], function () {
            Route::group(['prefix' => 'did_you_know_in_articles'], function () {
                Route::get('/', [\App\Http\Controllers\Api\Blocks\Article\DidYouKnowInArticlesController::class, 'index'])->name('didTouKnowInArticle.index');
                Route::get('/random/{count}/{category_id?}', [\App\Http\Controllers\Api\Blocks\Article\DidYouKnowInArticlesController::class, 'random'])->name('didTouKnowInArticle.random');
            });
        });

        Route::group(['prefix' => 'main-page'], function () {
            Route::group(['prefix' => 'popular-categories'], function () {
                Route::get('/', [\App\Http\Controllers\Api\Blocks\MainPage\PopularCategoriesController::class, 'index'])->name('popularCategories.index');
            });
            Route::group(['prefix' => 'most-talked-articles'], function () {
                Route::get('/', [\App\Http\Controllers\Api\Blocks\MainPage\MostTalkedArticlesController::class, 'index'])->name('mostTalkedArticles.index');
            });
            Route::group(['prefix' => 'big-card-articles'], function () {
                Route::get('/', [\App\Http\Controllers\Api\Blocks\MainPage\BigCardArticlesController::class, 'index'])->name('bigCardArticles.index');
                Route::get('/{id}', [\App\Http\Controllers\Api\Blocks\MainPage\BigCardArticlesController::class, 'show'])->name('bigCardArticles.show');
            });
            Route::group(['prefix' => 'readers-recommend-articles'], function () {
                Route::get('/', [\App\Http\Controllers\Api\Blocks\MainPage\ReadersRecomendArticlesController::class, 'index'])->name('readersRecommendArticles.index');
            });
        });

        Route::group(['prefix' => 'category'], function () {
            Route::group(['prefix' => 'everyone-talking-about'], function () {
                Route::get('/{category_slug}', [\App\Http\Controllers\Api\Blocks\Category\EveryoneTalkingAboutsController::class, 'index'])->name('everyoneTalkingAbout.index');
            });

            Route::group(['prefix' => 'qa-category'], function () {
                Route::get('/{category_slug}', [\App\Http\Controllers\Api\Blocks\Category\QACategoryController::class, 'index'])->name('QACategory.index');
            });
        });

        Route::group(['prefix' => 'info-block'], function () {
            Route::get('/{id}', [\App\Http\Controllers\Api\Blocks\InfoBlockController::class, 'show'])->name('infoBlock.show');
        });

        Route::group(['prefix' => 'sub-category'], function () {
            Route::group(['prefix' => 'top-facts-block'], function () {
                Route::get('/{category_slug}', [\App\Http\Controllers\Api\Blocks\SubCategory\SubCatTopFactsBlockController::class, 'index'])->name('subCatTopFacts.index');
            });
            Route::group(['prefix' => 'alphavite-block'], function () {
                Route::get('/{category_slug}', [\App\Http\Controllers\Api\Blocks\SubCategory\SubCatAlphaviteBlockController::class, 'index'])->name('subCatAlphavite.index');
            });
            Route::group(['prefix' => 'interesting-block'], function () {
                Route::get('/{category_slug}', [\App\Http\Controllers\Api\Blocks\SubCategory\SubCatInterestingBlockController::class, 'index'])->name('subCatInteresting.index');
            });
            Route::group(['prefix' => 'calendar-block'], function () {
                Route::get('/{category_slug}', [\App\Http\Controllers\Api\Blocks\SubCategory\SubCatCalendarBlockController::class, 'index'])->name('subCatCalendar.index');
            });
            Route::group(['prefix' => 'expert-advice-block'], function () {
                Route::get('/{category_slug}', [\App\Http\Controllers\Api\Blocks\SubCategory\SubCatExpertAdviceBlockController::class, 'index'])->name('subCatCalendar.index');
            });
            Route::group(['prefix' => 'game-one-block'], function () {
                Route::get('/{category_slug}', [\App\Http\Controllers\Api\Blocks\SubCategory\SubCatGameOneBlockController::class, 'index'])->name('subCatGameOne.index');
            });
            Route::group(['prefix' => 'game-two-block'], function () {
                Route::get('/{category_slug}', [\App\Http\Controllers\Api\Blocks\SubCategory\SubCatGameTwoBlockController::class, 'index'])->name('subCatGameTwo.index');
            });
            Route::group(['prefix' => 'behind-the-scenes-block'], function () {
                Route::get('/{category_slug}', [\App\Http\Controllers\Api\Blocks\SubCategory\SubCatBehindTheScenesBlockController::class, 'index'])->name('subBehindTheScenes.index');
            });
            Route::group(['prefix' => 'encyclopedia-block'], function () {
                Route::get('/{category_slug}', [\App\Http\Controllers\Api\Blocks\SubCategory\SubCatEncyclopediaBlockController::class, 'index'])->name('subCatEncyclopedia.index');
            });
            Route::group(['prefix' => 'know_more_about_each'], function () {
                Route::get('/{category_slug}', [\App\Http\Controllers\Api\Blocks\SubCategory\SubCatKnowMoreAboutEachBlockController::class, 'index'])->name('subCatGameTwo.index');
            });
        });

        Route::group(['prefix' => 'not-found'], function () {
            Route::group(['prefix' => 'popular-not-found-articles'], function () {
                Route::get('/', [\App\Http\Controllers\Api\Blocks\NotFound\PopularNotFoundArticlesController::class, 'index'])->name('popularNotFoundArticles.index');
            });
        });
    });

    Route::group(['prefix' => 'settings'], function () {
        Route::get('/', [\App\Http\Controllers\Api\SettingController::class, 'getAppSettings'])->name('api.settings.index');
    });

    Route::group(['prefix' => 'standalone-page'], function () {
        Route::get('/{id}', [\App\Http\Controllers\Api\StandalonePageController::class, 'show'])->name('standalone-page.show');
    });
});
