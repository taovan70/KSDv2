<?php

namespace App\Http\Controllers\Admin\Charts;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Backpack\CRUD\app\Http\Controllers\ChartController;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use DateTime;


class ArticlesInfoLastYearChartController extends ChartController
{

    function generateDatesForLast12Months()
    {
        $dates = array();
        $currentDate = new DateTime();

        for ($i = 0; $i < 12; $i++) {
            $dates[] = $currentDate->format('Y-m');
            $currentDate->modify('-1 month');
        }

        return array_reverse($dates); // Reverse the array to get dates in ascending order
    }

    public function setup()
    {
        $this->chart = new Chart();

        $this->chart->labels($this->generateDatesForLast12Months());
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
        $this->chart->load(backpack_url('charts/articles-info-last-year'));

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
        $dates = $this->generateDatesForLast12Months();

        foreach ($dates as $date) {
            // get year
            $year = substr($date, 0, 4);
            // get month
            $month = substr($date, 5, 2);

            $articles[] = Article::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->where('published', '1')
                ->count();

            $categories[] = Category::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();

            $subCategories[] = Category::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->where('depth', 2)
                ->count();

            $tags[] = Tag::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
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
