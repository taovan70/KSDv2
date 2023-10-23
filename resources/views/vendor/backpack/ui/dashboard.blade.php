@extends(backpack_view('blank'))

@php
    $articlesCount = \App\Models\Article::count();
    $categoriesFirstLevelCount = \App\Models\Category::where('depth', 1)->count();
    $categoriesSecondLevelCount = \App\Models\Category::where('depth', 2)->count();
    $categoriesThirdLevelCount = \App\Models\Category::where('depth', 3)->count();


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
                'wrapper' => ['class' => 'col-sm-12 col-sm-1 col-md-6 col-lg-3 col-xl-3'],
                'content' => [
                    'header' => 'Всего тематик',
                    'body' => "<h2>{$categoriesThirdLevelCount}</h2>",
                ],
            ],
            [
                'type' => 'card',
                'wrapper' => ['class' => 'col-sm-12 col-sm-1 col-md-6 col-lg-3 col-xl-3'],
                'content' => [
                    'header' => 'Всего разделов',
                    'body' => "<h2>{$categoriesFirstLevelCount}</h2>",
                ],
            ],
            [
                'type' => 'card',
                'wrapper' => ['class' => 'col-sm-12 col-sm-1 col-md-6 col-lg-3 col-xl-3'],
                'content' => [
                    'header' => 'Всего подразделов',
                    'body' => "<h2>{$categoriesSecondLevelCount}</h2>",
                ],
            ],
        ],
    ];
    $widgets['after_content'][] = [
        'type' => 'chart',
        'controller' => \App\Http\Controllers\Admin\Charts\ArticlesInfoChartController::class,
        'class' => 'card mb-2',
        'wrapper' => ['class' => 'col-md-6'],
        'content' => [
            'header' => 'Статистика по статьям',
        ],
    ];
@endphp

@section('content')
    <h2>Статистика</h2>
@endsection
