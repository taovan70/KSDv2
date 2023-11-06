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
        'class' => 'stat-top-line',
        'content' => [
            // widgets
            [
                'type' => 'card',
                'wrapper' => ['class' => 'stat-top-line-card'],
                'content' => [
                    'header' => 'Всего статей',
                    'body' => "<h2>{$articlesCount}</h2>",
                ],
            ],
            [
                'type' => 'card',
                'wrapper' => ['class' => 'stat-top-line-card'],
                'content' => [
                    'header' => 'Всего тэгов',
                    'body' => "<h2>{$tagsCount}</h2>",
                ],
            ],
            [
                'type' => 'card',
                'wrapper' => ['class' => 'stat-top-line-card'],
                'content' => [
                    'header' => 'Всего разделов',
                    'body' => "<h2>{$categoriesCount}</h2>",
                ],
            ],
            [
                'type' => 'card',
                'wrapper' => ['class' => 'stat-top-line-card'],
                'content' => [
                    'header' => 'Всего подразделов',
                    'body' => "<h2>{$subCategoriesCount}</h2>",
                ],
            ],
        ],
    ];

    $widgets['after_content'][] = [
        'type' => 'div',
        'class' => 'chart-page-second-line',
        'content' => [
           [
            'type' => 'div',
            'class' => 'chart-3-wrapper',
            'content' => [
                [
                    'type' => 'chart',
                    'controller' => \App\Http\Controllers\Admin\Charts\ArticlesInfoLastWeekChartController::class,
                    'class' => 'card mb-2',
                    'wrapper' => ['class' => 'chart1'],
                    'content' => [
                    ],
                ],
                [
                    'type' => 'chart',
                    'controller' => \App\Http\Controllers\Admin\Charts\ArticlesInfoCurrentMonthChartController::class,
                    'class' => 'card mb-2',
                    'wrapper' => ['class' => 'chart2'],
                    'content' => [
                    ],
                ],
                [
                    'type' => 'chart',
                    'controller' => \App\Http\Controllers\Admin\Charts\ArticlesInfoLastYearChartController::class,
                    'class' => 'card mb-2',
                    'wrapper' => ['class' => 'chart3'],
                    'content' => [
                    ],
                ],
            ]
           ],
           [
            'type' => 'div',
            'class' => 'chart-page-second-line__col-2',
            'content' => [
            // widgets
                    [
                        'type' => 'card',
                        'wrapper' => ['class' => 'chart-page-second-line__col-2--card'],
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
                        'wrapper' => ['class' => 'chart-page-second-line__col-2--card'],
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
            ]
        ]
    ];

@endphp

@section('content')
    <h2>Статистика</h2>

@endsection

@section('after_scripts')
<script>
  $( document ).ready(function() {
   const chartWrapper = $('.chart-3-wrapper')
    window.firstChart = $('.chart-3-wrapper .chart1')
    window.secondChart = $('.chart-3-wrapper .chart2')
    window.thirdChart = $('.chart-3-wrapper .chart3')
    window.secondChart.addClass('chart-hidden')
    window.thirdChart.addClass('chart-hidden')
    chartWrapper.prepend(`<div class="chart-wrapper-header">
                <div class='card-title mb-0'>Статистика в графиках </div>
                <div>
                <ul class="nav justify-content-end charts_switcher">
                  <li class="nav-item">
                    <a class="nav-link active" data-chart="week" href="#">Неделя</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" data-chart="month" href="#">Месяц</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" data-chart="year" href="#">Год</a>
                  </li>
                </ul>
                </div>
            </div>`)
  });

  $('body').on('click', '.charts_switcher', function(e){
    e.preventDefault();
    $(this).find('.active').removeClass('active')
    e.target.classList.add('active')
    if(e.target.dataset.chart === 'week'){
      window.firstChart.removeClass('chart-hidden')
      window.secondChart.addClass('chart-hidden')
      window.thirdChart.addClass('chart-hidden')
    }

    if(e.target.dataset.chart === 'month'){
      window.secondChart.removeClass('chart-hidden')
      window.firstChart.addClass('chart-hidden')
      window.thirdChart.addClass('chart-hidden')
    }

    if(e.target.dataset.chart === 'year'){
      window.thirdChart.removeClass('chart-hidden')
      window.firstChart.addClass('chart-hidden')
      window.secondChart.addClass('chart-hidden')
    }
  });
</script>
<style>
    .pl-0 {
        padding-left: 0;
    }
    .chart-3-wrapper {
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    .chart-wrapper-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .charts_switcher .nav-link.active {
        text-decoration: underline;
        text-underline-offset: 10px;
    }

    .chart-hidden {
        position: absolute;
        opacity: 0;
        top: 0;
        width: 1px;
        height: 1px;
        overflow: hidden;
    }

    .stat-top-line {
        display: flex;
        gap: 15px;
    }

    .stat-top-line-card {
        width: 100%;
    }

    .chart-page-second-line {
        display: flex;
        gap:15px
    }

    .chart-page-second-line__col-2 {
        display: flex;
        flex-direction: column;
        width: 100%;
        padding-top: 56px;
    }

    .chart-page-second-line__col-2--card .card-body {
        padding: 31px;
    }

    @media screen and (max-width: 768px) {
        .stat-top-line {
            flex-wrap: wrap;
            gap: 0;
        }
    }

    @media screen and (max-width: 1300px) {
        .chart-page-second-line {
            flex-wrap: wrap;
            gap: 0;
        }

        .chart-page-second-line__col-2 {
            padding-top: 20px;
        }

    }
</style>
@endsection
