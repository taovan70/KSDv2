<?php

namespace App\Http\Controllers\Admin\Charts;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\ChartController;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

/**
 * Class ArticlesInfoChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ArticlesInfoChartController extends ChartController
{
    public function setup()
    {
        $this->chart = new Chart();

        // MANDATORY. Set the labels for the dataset points
        $labels = [];
        for ($days_backwards = 30; $days_backwards >= 0; $days_backwards--) {
            if ($days_backwards == 1) {
            }
            $labels[] = $days_backwards.' days ago';
        }
        $this->chart->labels($labels);

        // RECOMMENDED. Set URL that the ChartJS library should call, to get its data using AJAX.
        $this->chart->load(backpack_url('charts/articles-info'));

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
         $users_created_today = User::whereDate('created_at', today())->count();

         for ($days_backwards = 30; $days_backwards >= 0; $days_backwards--) {
             // Could also be an array_push if using an array rather than a collection.
             $users[] = User::whereDate('created_at', today()
                 ->subDays($days_backwards))
                 ->count();
             $articles[] = Article::whereDate('created_at', today()
                 ->subDays($days_backwards))
                 ->count();
             $categories[] = Category::whereDate('created_at', today()
                 ->subDays($days_backwards))
                 ->count();
             $tags[] = Tag::whereDate('created_at', today()
                 ->subDays($days_backwards))
                 ->count();
         }

         $this->chart->dataset('Users', 'line', $users)
             ->color('rgb(77, 189, 116)')
             ->backgroundColor('rgba(77, 189, 116, 0.4)');

         $this->chart->dataset('Articles', 'line', $articles)
             ->color('rgb(96, 92, 168)')
             ->backgroundColor('rgba(96, 92, 168, 0.4)');

         $this->chart->dataset('Categories', 'line', $categories)
             ->color('rgb(255, 193, 7)')
             ->backgroundColor('rgba(255, 193, 7, 0.4)');

         $this->chart->dataset('Tags', 'line', $tags)
             ->color('rgba(70, 127, 208, 1)')
             ->backgroundColor('rgba(70, 127, 208, 0.4)');
     }
}
