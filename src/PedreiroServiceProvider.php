<?php

namespace Pedreiro;

use App;
use Config;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Muleta\Traits\Providers\ConsoleTools;
use Pedreiro\Commands\PedreiroCommand;
use Pedreiro\Elements\FormFields\After\DescriptionHandler;
use Pedreiro\Elements\FormFields\KeyValueJsonFormField;
use Pedreiro\Elements\FormFields\MultipleImagesWithAttrsFormField;
use Pedreiro\Events\FormFieldsRegistered;
use Pedreiro\Facades\Form;
use Pedreiro\Http\Middleware\isAjax;
use Pedreiro\Services\RiCaService;

class PedreiroServiceProvider extends ServiceProvider
{
    use ConsoleTools;

    public $packageName = 'pedreiro';
    const pathVendor = 'sierratecnologia/pedreiro';

    public static $aliasProviders = [
        'PedreiroURL' => \Pedreiro\Facades\PedreiroURL::class,

        'Active' => \Pedreiro\Facades\Active::class,

        'Flash' => \Laracasts\Flash\Flash::class,
        'Gravatar' => \Creativeorange\Gravatar\Facades\Gravatar::class,

        /**
         * Listagens
         */
        'DataTables' => \Yajra\DataTables\Facades\DataTables::class,
        
        'Active' => \HieuLe\Active\Facades\Active::class,

        'Translation' => \Translation\Facades\Translation::class,
        'TranslationCache' => \Translation\Facades\TranslationCache::class,
        // Form field generation
        'Former' => \Former\Facades\Former::class,
    ];

    // public static $providers = [
    public static $providers = [
        // HAML
        \Bkwld\LaravelHaml\ServiceProvider::class,
        /**
         * Layoults
         */
        \RicardoSierra\Minify\MinifyServiceProvider::class,
        \Collective\Html\HtmlServiceProvider::class,
        \Laracasts\Flash\FlashServiceProvider::class,

        \JeroenNoten\LaravelAdminLte\AdminLteServiceProvider::class,

        /**
         * Listagens
         */
        \Yajra\DataTables\DataTablesServiceProvider::class,

        /**
         * VEio pelo Facilitador
         **/
        \Former\FormerServiceProvider::class,
        \Bkwld\Upchuck\ServiceProvider::class,

        \Translation\TranslationServiceProvider::class,
        
        /**
         * Helpers
         */
        \HieuLe\Active\ActiveServiceProvider::class,
        \Laracasts\Flash\FlashServiceProvider::class,
        /**
         * Outros
         */
        \Laravel\Tinker\TinkerServiceProvider::class,

        \Pedreiro\RoutesExplorerServiceProvider::class,
    ];
    
    public static $menuItens = [
        [
            'text' => 'Painel',
            'order' => 501,
            'url' => 'painel',
            // 'dontSection'     => 'painel',
            'topnav' => true,
            'active' => ['painel', 'painel*', 'regex:@^painel/[0-9]+$@'],
        ],
        [
            'text' => 'Master',
            'order' => 1001,
            'url' => 'master',
            // 'dontSection'     => 'master',
            'topnav' => true,
            'active' => ['master', 'master*', 'regex:@^master/[0-9]+$@'],
        ],
        [
            'text' => 'Administração',
            'order' => 2001,
            'url' => 'admin',
            // 'dontSection'     => 'admin',
            'topnav' => true,
            'active' => ['admin', 'admin*', 'regex:@^admin/[0-9]+$@'],
        ],
        [
            'text' => 'RiCa',
            'order' => 4001,
            'url' => 'rica',
            // 'dontSection'     => 'rica',
            'topnav' => true,
            'active' => ['rica', 'rica*', 'regex:@^rica/[0-9]+$@'],
        ],
        [
            'text'        => 'Painel Dashboard',
            'route'       => 'painel.porteiro.dashboard',
            'icon'        => 'fas fa-fw fa-industry',
            'icon_color'  => 'blue',
            'label_color' => 'success',
            'section'     => 'painel',
            'order' => 502,
            // 'access' => \Porteiro\Models\Role::$ADMIN
        ],


        /**
         * @todo fazer esses tres dashboards
         */
        // [
        //     'text'        => 'Master Dashboard',
        //     'route'       => 'master.porteiro.dashboard',
        //     'icon'        => 'fas fa-fw fa-industry',
        //     'icon_color'  => 'blue',
        //     'label_color' => 'success',
        //     'section'     => 'master',
        //     'order' => 1002,
        //     // 'access' => \Porteiro\Models\Role::$ADMIN
        // ],
        // [
        //     'text'        => 'Admin Dashboard',
        //     'route'       => 'admin.porteiro.dashboard',
        //     'icon'        => 'fas fa-fw fa-industry',
        //     'icon_color'  => 'blue',
        //     'label_color' => 'success',
        //     'section'     => 'admin',
        //     'order' => 2001,
        //     // 'access' => \Porteiro\Models\Role::$ADMIN
        // ],
        // [
        //     'text'        => 'RiCa Dashboard',
        //     'route'       => 'rica.porteiro.dashboard',
        //     'icon'        => 'fas fa-fw fa-industry',
        //     'icon_color'  => 'blue',
        //     'label_color' => 'success',
        //     'section'     => 'rica',
        //     'order' => 4002,
        //     // 'access' => \Porteiro\Models\Role::$ADMIN
        // ],
    ];

    
    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }


        /**
         * Porteiro; Routes
         */
        $this->loadRoutesForRiCa(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'routes');
    }
    
    public function boot(Router $router, Dispatcher $event)
    {

        // Define constants that Decoy uses
        if (!defined('FORMAT_DATE')) {
            define('FORMAT_DATE', __('pedreiro::base.constants.format_date'));
        }
        if (!defined('FORMAT_DATETIME')) {
            define('FORMAT_DATETIME', __('pedreiro::base.constants.format_datetime'));
        }
        if (!defined('FORMAT_TIME')) {
            define('FORMAT_TIME', __('pedreiro::base.constants.format_time'));
        }
        
        // Paginator Bootstrap
        // @todo tava dando erro
        // syntax error, unexpected ''pagination.previous'' (T_CONSTANT_ENCAPSED_STRING), expecting ';' or ',' (View: /var/www/html/vendor/laravel/framework/src/Illuminate/Pagination/resources/views/bootstrap-4.blade.php) (View: /var/www/html/vendor/laravel/framework/src/Illuminate/Pagination/resources/views/bootstrap-4.blade.php)
        // Paginator::useBootstrap();


        $this->loadTranslations();
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                __DIR__ . '/../config/pedreiro.php' => config_path('pedreiro.php'),
                ],
                'config'
            );

            $this->publishes(
                [
                __DIR__ . '/../resources/views' => base_path('resources'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'pedreiro'),
                ],
                'views'
            );

            // $migrationFileName = 'create_pedreiro_table.php';
            // if (! $this->migrationFileExists($migrationFileName)) {
            //     $this->publishes([
            //         __DIR__ . "/../database/migrations/{$migrationFileName}.stub" => database_path('migrations/' . date('Y_m_d_His', time()) . '_' . $migrationFileName),
            //     ], 'migrations');
            // }

            $this->commands(
                [
                PedreiroCommand::class,
                ]
            );
        }
        $router->aliasMiddleware('isAjax', isAjax::class);

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'pedreiro');

        // COloquei no register pq nao tava reconhecendo as rotas para o adminlte
        $this->app->booted(
            function () {
                $this->routes();
            }
        );

        /**
         * Load Active https://github.com/letrunghieu/active
         */
        // Update the instances each time a request is resolved and a route is matched
        $instance = app('active');
        app('router')->matched(
            function (RouteMatched $event) use ($instance) {
                $instance->updateInstances($event->route, $event->request);
            }
        );

        // Add strip_tags validation rule
        Validator::extend(
            'strip_tags',
            function ($attribute, $value) {
                return strip_tags($value) === $value;
            },
            trans('validation.invalid_strip_tags')
        );

        // //ExtendedBreadFormFieldsServiceProvider
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'extended-fields');
        $this->registerAlertComponents();
        // Config Former
        $this->configureFormer();


        $this->registerViewComposers();


        $this->eloquentSvents();
        $this->setProviders();
    }
    /**
     * Delegate events to Decoy observers
     *
     * @return void
     */
    protected function eloquentSvents()
    {
        $this->app['events']->listen(
            'eloquent.saved:*',
            '\Pedreiro\Observers\ManyToManyChecklist'
        );
    }



    public function register()
    {
        
        $loader = AliasLoader::getInstance();
        $loader->alias('Form', Form::class);
        $loader->alias('Pedreiro', PedreiroFacade::class);
        $this->app->singleton(
            'pedreiro',
            function ($app) {
                return app()->make(Pedreiro::class);
            }
        );
        // $this->app->bind('pedreiro', Pedreiro::class);
        $loader->alias('Siravel', \Pedreiro\Facades\RiCaServiceFacade::class); // @todo ??? que porra é essa ?
        $loader->alias('RiCaService', \Pedreiro\Facades\RiCaServiceFacade::class);
        $this->app->bind(
            'RiCaService',
            function ($app) {
                return new RiCaService();
            }
        );


        $this->mergeConfigFrom(__DIR__ . '/../config/pedreiro.php', 'pedreiro');
  

        // Register URL Generators as "PedreiroURL".
        $this->app->singleton(
            'pedreiro.url',
            function ($app) {
                return new \Pedreiro\Routing\UrlGenerator($app['request']->path());
            }
        );
        // Build the Breadcrumbs store
        $this->app->singleton(
            'rica.breadcrumbs',
            function ($app) {
                $breadcrumbs = new \Pedreiro\Template\Layout\Breadcrumbs();
                $breadcrumbs->set($breadcrumbs->parseURL());

                return $breadcrumbs;
            }
        );



        // Register Migrations
        $this->loadMigrationsFrom(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'migrations');

        /**
         * Load Active https://github.com/letrunghieu/active
         */
        $this->app->singleton(
            'active',
            function ($app) {
                $instance = new Active($app['router']->getCurrentRequest());

                return $instance;
            }
        );


        $loader->alias('FormMaker', \Pedreiro\Facades\FormMaker::class);
        $this->app->singleton(
            'form-maker',
            function () {
                return new \SierraTecnologia\FormMaker\Services\FormMaker();
            }
        );

        // ExtendedBreadFormFieldsServiceProvider

        PedreiroFacade::addFormField(KeyValueJsonFormField::class);
        PedreiroFacade::addFormField(MultipleImagesWithAttrsFormField::class);
        $this->registerFormFields();


        // Build the Elements collection
        $this->app->singleton(
            'pedreiro.elements', function ($app) {
                return with(new \Pedreiro\Collections\Elements)->setModel(\Support\Models\Element::class);
            }
        );

        // $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'pedreiro');
    }

    public static function migrationFileExists(string $migrationFileName): bool
    {
        $len = strlen($migrationFileName);
        foreach (glob(database_path("migrations/*.php")) as $filename) {
            if ((substr($filename, -$len) === $migrationFileName)) {
                return true;
            }
        }

        return false;
    }
    protected function registerFormFields()
    {
        $formFields = [
            'checkbox',
            'multiple_checkbox',
            'color',
            'date',
            'file',
            'image',
            'multiple_images',
            'media_picker',
            'number',
            'password',
            'radio_btn',
            'rich_text_box',
            'code_editor',
            'markdown_editor',
            'select_dropdown',
            'select_multiple',
            'text',
            'text_area',
            'time',
            'timestamp',
            'hidden',
            'coordinates',
        ];

        foreach ($formFields as $formField) {
            $class = Str::studly("{$formField}_handler");

            PedreiroFacade::addFormField("Pedreiro\\Elements\\FormFields\\{$class}");
        }

        // PedreiroFacade::addAfterFormField(DescriptionHandler::class);

        event(new FormFieldsRegistered($formFields));
    }

    /**
     * Register alert components.
     */
    protected function registerAlertComponents()
    {
        $components = ['title', 'text', 'button'];

        foreach ($components as $component) {
            $class = 'Pedreiro\\Elements\\Alert\\'.ucfirst(Str::camel($component)).'Component';

            $this->app->bind("pedreiro.alert.components.{$component}", $class);
        }
    }

    /**
     * Register view composers.
     */
    protected function registerViewComposers()
    {
        // Register alerts
        View::composer(
            'pedreiro::*',
            function ($view) {
                $view->with('alerts', PedreiroFacade::alerts());
            }
        );
    }
    /**
     * Config Former
     *
     * @return void
     */
    protected function configureFormer()
    {
        // Use Bootstrap 3
        Config::set('former.framework', 'TwitterBootstrap3');

        // Reduce the horizontal form's label width
        Config::set('former.TwitterBootstrap3.labelWidths', []);

        // @todo desfazer pq da erro qnd falta tabela model_translactions
        // // Change Former's required field HTML
        // Config::set(
        //     'former.required_text', ' <span class="glyphicon glyphicon-exclamation-sign js-tooltip required" title="' .
        //     __('pedreiro::login.form.required') . '"></span>'
        // );

        // Make pushed checkboxes have an empty string as their value
        Config::set('former.unchecked_value', '');

        // Add Facilitador's custom Fields to Former so they can be invoked using the "Former::"
        // namespace and so we can take advantage of sublassing Former's Field class.
        $this->app['former.dispatcher']->addRepository('Pedreiro\\Elements\\Fields\\');
    }
    protected function loadTranslations()
    {
        // Publish lanaguage files
        $this->publishes(
            [
            $this->getResourcesPath('lang') => resource_path('lang'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'pedreiro'),
            ],
            ['lang',  'sitec', 'sitec-lang', 'translations']
        );

        // Load translations
        $this->loadTranslationsFrom($this->getResourcesPath('lang'), 'pedreiro');
    }
}
