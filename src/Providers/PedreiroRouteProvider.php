<?php

namespace Pedreiro\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class PedreiroRouteProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Pedreiro\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function map(Router $router): void
    {
        // $router->group(
        //     ['namespace' => $this->namespace], function ($router) {
        //         include __DIR__.'/../../routes/api/api.php';
        //         include __DIR__.'/../../routes/api/cms.php';
        //         include __DIR__.'/../../routes/api/v1.php';
        //     }
        // );
    }
}
