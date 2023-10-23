<?php

namespace App\Http\Controllers\Admin\Charts;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Backpack\CRUD\app\Http\Controllers\ChartController;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use DateTime;


class ArticlesInfoCurrentMonthChartController extends ChartController
{

    private function generateDatesForPreviousMonth()
    {
        $dates = array();
        $currentDate = new DateTime();

        for ($i = 0; $i < 30; $i++) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->modify('-1 day');
        }

        return array_reverse($dates);
    }
    public function setup()
    {
        $this->chart = new Chart();

        $this->chart->labels($this->generateDatesForPreviousMonth());
        $this->chart->options([
            'scales' => [
                'yAxes' => [
                    [
                        'ticks' => [
                            'stepSize' => 1
                        ],
                        'scaleLabel' => [
                            'display' => true,
                            'labelString' => 'Количество',
                        ],
                    ],
                ],
            ],
        ]);

        // RECOMMENDED. Set URL that the ChartJS library should call, to get its data using AJAX.
        $this->chart->load(backpack_url('charts/articles-info-current-month'));

        // OPTIONAL
        $this->chart->minimalist(false);
        $this->chart->displayLegend(true);
    }

    /**
     * Respond to AJAX calls with all the chart data points.
     *
     * @return json
     */
    public function data()
    {
        $dates = $this->generateDatesForPreviousMonth();

        foreach ($dates as $date) {
            $articles[] = Article::whereDate('created_at', $date)
                ->where('published', '1')
                ->count();
            $categories[] = Category::whereDate('created_at', $date)
                ->count();
            $subCategories[] = Category::whereDate('created_at', $date)
                ->where('depth', 2)
                ->count();
            $tags[] = Tag::whereDate('created_at', $date)
                ->count();
        }

        $this->chart->dataset('Статей', 'line', $articles)
            ->color('rgb(96, 92, 168)')
            ->backgroundColor('rgba(96, 92, 168, 0.4)');

        $this->chart->dataset('Категорий', 'line', $categories)
            ->color('rgb(255, 193, 7)')
            ->backgroundColor('rgba(255, 193, 7, 0.4)');

        $this->chart->dataset('Подкатегорий', 'line', $subCategories)
            ->color('rgb(239, 17, 105)')
            ->backgroundColor('rgba(239, 17, 105, 0.4)');

        $this->chart->dataset('Тэгов', 'line', $tags)
            ->color('rgba(70, 127, 208, 1)')
            ->backgroundColor('rgba(70, 127, 208, 0.4)');
    }
}
