<?php

namespace App\Providers;

use App\Http\Middleware\ShareClareStore;
use App\Services\ClareStore;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ClareStore::class);
    }

    public function boot(): void
    {
        EncryptCookies::except(['clare_newsletter', 'clare_cookie']);

        $this->app['router']->pushMiddlewareToGroup('web', ShareClareStore::class);

        View::composer('*', function ($view) {
            app(ClareStore::class)->share();
            App::setLocale(app(ClareStore::class)->locale());
        });
    }
}
