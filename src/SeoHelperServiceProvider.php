<?php

declare(strict_types=1);

namespace Arcanedev\SeoHelper;

use Arcanedev\SeoHelper\Contracts\SeoHelper as SeoHelperContract;
use Arcanedev\SeoHelper\Contracts\SeoMeta as SeoMetaContract;
use Arcanedev\SeoHelper\Contracts\SeoOpenGraph as SeoOpenGraphContract;
use Arcanedev\SeoHelper\Contracts\SeoTwitter as SeoTwitterContract;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use function config_path;

/**
 * Class     SeoHelperServiceProvider
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SeoHelperServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Package name.
     *
     * @var string
     */
    protected $package = 'seo-helper';

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/seo-helper.php',
            'seo-helper'
        );

        $this->app->singleton(SeoHelperContract::class, SeoHelper::class);
        $this->app->singleton(SeoMetaContract::class, function ($app) {
            return new SeoMeta($app['config']->get('seo-helper'));
        });
        $this->app->singleton(SeoOpenGraphContract::class, function ($app) {
            return new SeoOpenGraph($app['config']->get('seo-helper'));
        });
        $this->app->singleton(SeoTwitterContract::class, function ($app) {
            return new SeoTwitter($app['config']->get('seo-helper'));
        });
    }

    /**
     * Boot the service provider.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/seo-helper.php' => config_path('seo-helper.php'),
            ], 'config');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            SeoHelperContract::class,
            SeoMetaContract::class,
            SeoOpenGraphContract::class,
            SeoTwitterContract::class,
        ];
    }
}
