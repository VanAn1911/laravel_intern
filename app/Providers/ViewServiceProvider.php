<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('adminlte::page', function ($view) {
            $view->with('something', 'value');
        });
    }
}