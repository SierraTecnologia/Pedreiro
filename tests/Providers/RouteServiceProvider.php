<?php

namespace Pedreiro\Test\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Pedreiro\Test\Controllers';

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            $router->get('/', function () {
                return 'home';
            });

            $router->group(['middleware' => 'web'], function ($router) {
                $router->resource('/post', 'PostController');
                $router->put('/post/{post}/restore', 'PostController@restore');
            });
        });
    }
}
