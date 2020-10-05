<?php

namespace Pedreiro;

use App;
use Config;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\AliasLoader;
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
use Support\Facades\Support as SupportFacade;

class PedreiroServiceProvider extends ServiceProvider
{
    use ConsoleTools;

    public $packageName = 'pedreiro';
    const pathVendor = 'sierratecnologia/pedreiro';

    public static $aliasProviders = [
        'Active' => \Pedreiro\Facades\Active::class,

        'Flash' => \Laracasts\Flash\Flash::class,
        'Gravatar' => Creativeorange\Gravatar\Facades\Gravatar::class,
        'DataTables' => Yajra\DataTables\Facades\DataTables::class,
        'Active' => HieuLe\Active\Facades\Active::class,

        'Translation' => Translation\Facades\Translation::class,
        'TranslationCache' => Translation\Facades\TranslationCache::class,
        // Form field generation
        'Former' => \Former\Facades\Former::class,
    ];

    // public static $providers = [
    public static $providers = [
            /**
             * Layoults
             */
            \RicardoSierra\Minify\MinifyServiceProvider::class,
            \Collective\Html\HtmlServiceProvider::class,
            \Laracasts\Flash\FlashServiceProvider::class,
    
            JeroenNoten\LaravelAdminLte\AdminLteServiceProvider::class,
            /**
             * VEio pelo Facilitador
             **/
            \Former\FormerServiceProvider::class,
            \Bkwld\Upchuck\ServiceProvider::class,
    
            Translation\TranslationServiceProvider::class,
            /**
             * Helpers
             */
            HieuLe\Active\ActiveServiceProvider::class,
            Laracasts\Flash\FlashServiceProvider::class,
            /**
             * Outros
             */
            \Laravel\Tinker\TinkerServiceProvider::class,

            \Pedreiro\RoutesExplorerServiceProvider::class
        ];
    
    public static $menuItens = [
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
        $this->loadRoutesForRiCa(__DIR__.'/../routes');
    }
    
    public function boot(Router $router, Dispatcher $event)
    {
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
                __DIR__ . '/../resources/views' => base_path('resources/views/vendor/pedreiro'),
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

        $this->mergeConfigFrom(__DIR__ . '/../config/pedreiro.php', 'pedreiro');
  

        // Register Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

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
                return new \Grafite\Forms\Services\FormMaker();
            }
        );

        // ExtendedBreadFormFieldsServiceProvider

        PedreiroFacade::addFormField(KeyValueJsonFormField::class);
        PedreiroFacade::addFormField(MultipleImagesWithAttrsFormField::class);
        $this->registerFormFields();


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
            $class = 'Support\\Elements\\Alert\\'.ucfirst(Str::camel($component)).'Component';

            $this->app->bind("facilitador.alert.components.{$component}", $class);
        }
    }

    /**
     * Register view composers.
     */
    protected function registerViewComposers()
    {
        // Register alerts
        View::composer(
            'support::*',
            function ($view) {
                $view->with('alerts', SupportFacade::alerts());
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
        //     __('facilitador::login.form.required') . '"></span>'
        // );

        // Make pushed checkboxes have an empty string as their value
        Config::set('former.unchecked_value', '');

        // Add Facilitador's custom Fields to Former so they can be invoked using the "Former::"
        // namespace and so we can take advantage of sublassing Former's Field class.
        $this->app['former.dispatcher']->addRepository('Support\\Elements\\Fields\\');
    }
    protected function loadTranslations()
    {
        // Publish lanaguage files
        $this->publishes(
            [
            $this->getResourcesPath('lang') => resource_path('lang/vendor/support')
            ],
            ['lang',  'sitec', 'sitec-lang', 'translations']
        );

        // Load translations
        $this->loadTranslationsFrom($this->getResourcesPath('lang'), 'support');
    }
}
