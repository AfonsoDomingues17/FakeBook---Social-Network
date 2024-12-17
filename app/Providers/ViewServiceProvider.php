<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Country;
use App\Models\Category;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share the $countries variable with all views
        View::composer('*', function ($view) {
            $countries = Country::orderBy('name', 'asc')->get();
            $categories = Category::orderBy('name', 'asc')->get();
            $view->with('countries', $countries);
            $view->with('categories', $categories);
        });
    }
}
