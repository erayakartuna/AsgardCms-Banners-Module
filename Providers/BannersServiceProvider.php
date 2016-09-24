<?php namespace Modules\Banners\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Banners\Composers\BannersSliderComposer;

class BannersServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();
        view()->composer('home',BannersSliderComposer::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

    private function registerBindings()
    {
        $this->app->bind(
            'Modules\Banners\Repositories\BannersRepository',
            function () {
                $repository = new \Modules\Banners\Repositories\Eloquent\EloquentBannersRepository(new \Modules\Banners\Entities\Banners());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Banners\Repositories\Cache\CacheBannersDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Banners\Repositories\GroupsRepository',
            function () {
                $repository = new \Modules\Banners\Repositories\Eloquent\EloquentGroupsRepository(new \Modules\Banners\Entities\Groups());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Banners\Repositories\Cache\CacheGroupsDecorator($repository);
            }
        );
// add bindings


    }
}
