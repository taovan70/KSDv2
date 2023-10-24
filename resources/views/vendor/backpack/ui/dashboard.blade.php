@extends(backpack_view('blank'))

@php
    $startDate30DaysAgo = Carbon\Carbon::now()->subDays(30);
    $startDate365DaysAgo = Carbon\Carbon::now()->subDays(365);

    $articleCount30DaysAgo = \App\Models\Article::where('published', 1)
        ->whereDate('created_at', '>=', $startDate30DaysAgo)
        ->count();
    $categoriesCount30DaysAgo = \App\Models\Category::whereDate('created_at', '>=', $startDate30DaysAgo)
        ->where('depth', 0)
        ->orWhere('depth', 1)
        ->count();
    $subCategoriesCount30DaysAgo = \App\Models\Category::whereDate('created_at', '>=', $startDate30DaysAgo)
        ->where('depth', '>', 1)
        ->count();
    $tagsCount30DaysAgo = \App\Models\Tag::whereDate('created_at', '>=', $startDate30DaysAgo)->count();
    $articleCount365DaysAgo = \App\Models\Article::where('published', 1)
        ->whereDate('created_at', '>=', $startDate365DaysAgo)
        ->count();
    $categoriesCount365DaysAgo = \App\Models\Category::whereDate('created_at', '>=', $startDate365DaysAgo)
        ->where('depth', 0)
        ->orWhere('depth', 1)
        ->count();
    $subCategoriesCount365DaysAgo = \App\Models\Category::whereDate('created_at', '>=', $startDate365DaysAgo)
        ->where('depth', '>', 1)
        ->count();
    $tagsCount365DaysAgo = \App\Models\Tag::whereDate('created_at', '>=', $startDate365DaysAgo)->count();

    $articlesCount = \App\Models\Article::where('published', '1')->count();
    $categoriesCount = \App\Models\Category::where('depth', 0)
        ->orWhere('depth', 1)
        ->count();
    $subCategoriesCount = \App\Models\Category::where('depth', '>', 1)->count();
    $tagsCount = \App\Models\Tag::count();

    $widgets['after_content'][] = [
        'type' => 'div',
        'class' => 'd-flex flex-wrap',
        'content' => [
            // widgets
            [
                'type' => 'card',
                'wrapper' => ['class' => 'col-sm-12 col-md-6 col-lg-3 col-xl-3'],
                'content' => [
                    'header' => 'Всего статей',
                    'body' => "<h2>{$articlesCount}</h2>",
                ],
            ],
            [
                'type' => 'card',
                'wrapper' => ['class' => 'col-sm-12 col-md-6 col-lg-3 col-xl-3'],
                'content' => [
                    'header' => 'Всего тэгов',
                    'body' => "<h2>{$tagsCount}</h2>",
                ],
            ],
            [
                'type' => 'card',
                'wrapper' => ['class' => 'col-sm-12 col-md-6 col-lg-3 col-xl-3'],
                'content' => [
                    'header' => 'Всего разделов',
                    'body' => "<h2>{$categoriesCount}</h2>",
                ],
            ],
            [
                'type' => 'card',
                'wrapper' => ['class' => 'col-sm-12 col-md-6 col-lg-3 col-xl-3'],
                'content' => [
                    'header' => 'Всего подразделов',
                    'body' => "<h2>{$subCategoriesCount}</h2>",
                ],
            ],
        ],
    ];

    $widgets['after_content'][] = [
        'type' => 'div',
        'class' => 'd-flex flex-wrap',
        'content' => [
            // widgets
            [
                'type' => 'chart',
                'controller' => \App\Http\Controllers\Admin\Charts\ArticlesInfoCurrentMonthChartController::class,
                'class' => 'card mb-2',
                'wrapper' => ['class' => 'col-md-6'],
                'content' => [
                    'header' => 'Статистика по статьям за последние 30 дней',
                ],
            ],
            [
                'type' => 'chart',
                'controller' => \App\Http\Controllers\Admin\Charts\ArticlesInfoLastYearChartController::class,
                'class' => 'card mb-2',
                'wrapper' => ['class' => 'col-md-6'],
                'content' => [
                    'header' => 'Статистика по статьям за последний год',
                ],
            ],
        ],
    ];

    $widgets['after_content'][] = [
        'type' => 'div',
        'class' => 'd-flex flex-wrap mt-3',
        'content' => [
            // widgets
            [
                'type' => 'card',
                'wrapper' => ['class' => 'col-sm-12 col-md-6 col-lg-6 col-xl-6'],
                'content' => [
                    'header' => 'Итого за последние 30 дней',
                    'body' => "<div>
                        <div>Статей: {$articleCount30DaysAgo}</div>
                        <div>Категорий: {$categoriesCount30DaysAgo}</div>
                        <div>Подкатегорий: {$subCategoriesCount30DaysAgo}</div>
                        <div>Тегов: {$tagsCount30DaysAgo}</div>
                    </div>",
                ],
            ],
            [
                'type' => 'card',
                'wrapper' => ['class' => 'col-sm-12 col-md-6 col-lg-6 col-xl-6'],
                'content' => [
                    'header' => 'Итого за последние 365 дней',
                    'body' => "<div>
                        <div>Статей: {$articleCount365DaysAgo}</div>
                        <div>Категорий: {$categoriesCount365DaysAgo}</div>
                        <div>Подкатегорий: {$subCategoriesCount365DaysAgo}</div>
                        <div>Тегов: {$tagsCount365DaysAgo}</div>
                    </div>",
                ],
            ],
        ],
    ];

@endphp

@section('content')
    <h2>Статистика</h2>
@endsection
