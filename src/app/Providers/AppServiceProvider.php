<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Segment\Segment;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Init segment
        Segment::init(env('SEGMENT_KEY'));
    }
}
