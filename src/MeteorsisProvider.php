<?php

namespace Cuby\Meteorsis;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;
use Cuby\Meteorsis\Providers\EventServiceProvider;

class MeteorsisProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // 設定檔處理
        $services_source = realpath($raw = __DIR__.'/../config/services.php') ?: $raw;
        $meteorsis_source = realpath($raw = __DIR__.'/../config/Meteorsis.php') ?: $raw;

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$services_source => config_path('services.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('services');
            $this->app->configure('Meteorsis');
        }

        if ($this->app instanceof LaravelApplication && ! $this->app->configurationIsCached()) {
            $this->mergeConfigFrom($services_source, 'services');
            $this->mergeConfigFrom($meteorsis_source, 'Meteorsis');
        }

        // callback處理
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }
}