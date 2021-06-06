<?php

namespace Pedreiro\Providers;

use App;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Pedreiro\Services\BlogService;
use Pedreiro\Services\EventService;
use Pedreiro\Services\ModuleService;
use Pedreiro\Services\Negocios\PageService;

class PedreiroServiceProvider extends ServiceProvider
{
    /**
     * Register the services.
     */
    public function register()
    {
        // $loader = AliasLoader::getInstance();

        // $loader->alias('PageService', \Pedreiro\Facades\PageServiceFacade::class);
        // $loader->alias('EventService', \Pedreiro\Facades\EventServiceFacade::class);
        // $loader->alias('ModuleService', \Pedreiro\Facades\ModuleServiceFacade::class);
        // $loader->alias('BlogService', \Pedreiro\Facades\BlogServiceFacade::class);
        // $loader->alias('FileService', \MediaManager\Services\FileService::class);


        // $this->app->bind(
        //     'PageService', function ($app) {
        //         return new PageService();
        //     }
        // );

        // $this->app->bind(
        //     'EventService', function ($app) {
        //         return App::make(EventService::class);
        //     }
        // );

        // $this->app->bind(
        //     'ModuleService', function ($app) {
        //         return new ModuleService();
        //     }
        // );

        // $this->app->bind(
        //     'BlogService', function ($app) {
        //         return new BlogService();
        //     }
        // );
    }
}
