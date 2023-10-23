<?php

namespace App\Http\Controllers\Admin\Charts;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Backpack\CRUD\app\Http\Controllers\ChartController;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use DateTime;


class ArticlesInfoCurrentYearChartController extends ChartController
{

    private function generateDatesForPreviousMonth()
    {
        $dates = array();
        $firstDay = new DateTime('first day of last month');
        $lastDay = new DateTime('last day of last month');

        while ($firstDay <= $lastDay) {
            $dates[] = $firstDay->format('Y-m-d');
            $firstDay->modify('+1 day');
        }

        return $dates;
    }
    public function setup()
    {
        $this->chart = new Chart();

        // MANDATORY. Set the labels for the dataset points
        $labels = [];
        for ($days_backwards = 30; $days_backwards >= 0; $days_backwards--) {
            if ($days_backwards == 1) {
            }
            $labels[] = $days_backwards . ' дней назад';
        }
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
        $this->chart->load(backpack_url('charts/articles-info-last-month'));

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

        for ($days_backwards = 30; $days_backwards >= 0; $days_backwards--) {
            // Could also be an array_push if using an array rather than a collection.
            $articles[] = Article::whereDate('created_at', today()
                ->subDays($days_backwards))
                ->where('published', '1')
                ->count();
            $categories[] = Category::whereDate('created_at', today()
                ->subDays($days_backwards))
                ->count();
            $subCategories[] = Category::whereDate('created_at', today()
                ->subDays($days_backwards))
                ->where('depth', 2)
                ->count();
            $tags[] = Tag::whereDate('created_at', today()
                ->subDays($days_backwards))
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
