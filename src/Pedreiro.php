<?php

namespace Pedreiro;

use Bkwld\Library;
use Config;
use Facilitador\Models\Setting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Pedreiro\Elements\FormFields\After\HandlerInterface as AfterHandlerInterface;
use Pedreiro\Elements\FormFields\HandlerInterface;
use Pedreiro\Events\AlertsCollection;
use Pedreiro\Template\Actions\DeleteAction;
use Pedreiro\Template\Actions\EditAction;
use Pedreiro\Template\Actions\RestoreAction;
use Pedreiro\Template\Actions\ViewAction;
use ReflectionClass;
use Request;
use Session;
use Siravel\Models\Negocios\Page;
use View;

class Pedreiro
{
    protected $version;
    protected $filesystem;

    protected $urlSection = null;
    protected $urlSectionOptions = [
        'painel',
        'master',
        'admin',
        'rica',
    ];

    protected $alerts = [];
    protected $alertsCollected = false;

    protected $formFields = [];
    protected $afterFormFields = [];

    protected $viewLoadingEvents = [];
    
    protected $influenciaModel = false;

    protected $actions = [
        DeleteAction::class,
        RestoreAction::class,
        EditAction::class,
        ViewAction::class,
    ];

    public $setting_cache = null;

    public function __construct()
    {
        $this->filesystem = app(Filesystem::class);

        $this->findVersion();
    }

    public function getUrlSection()
    {
        if (! $this->urlSection) {
            $urlSection = Request::segment(1);
            if (! in_array($urlSection, $this->urlSectionOptions)) {
                $urlSection = Session::get('url_section');
            }
            if (! $urlSection) {
                $urlSection = $this->urlSectionOptions[0];
            }
            $this->urlSection = $urlSection;
            Session::put('url_section', $this->urlSection);
        }

        return $this->urlSection;
    }

    public function view($name, array $parameters = [])
    {
        foreach (Arr::get($this->viewLoadingEvents, $name, []) as $event) {
            $event($name, $parameters);
        }

        return view($name, $parameters);
    }

    public function onLoadingView($name, \Closure $closure)
    {
        if (! isset($this->viewLoadingEvents[$name])) {
            $this->viewLoadingEvents[$name] = [];
        }

        $this->viewLoadingEvents[$name][] = $closure;
    }

    public function formField($row, $dataType, $dataTypeContent)
    {
        if (empty($this->formFields)) {
            $this->registerFormFields();
        }
        // dd($this->formFields);
        $formField = $this->formFields[$row->type];

        return $formField->handle($row, $dataType, $dataTypeContent);
    }

    public function afterFormFields($row, $dataType, $dataTypeContent)
    {
        return collect($this->afterFormFields)->filter(
            function ($after) use ($row, $dataType, $dataTypeContent) {
                return $after->visible($row, $dataType, $dataTypeContent, $row->details);
            }
        );
    }

    public function addFormField($handler)
    {
        if (! $handler instanceof HandlerInterface) {
            $handler = app($handler);
        }

        $this->formFields[$handler->getCodename()] = $handler;

        return $this;
    }

    public function addAfterFormField($handler)
    {
        if (! $handler instanceof AfterHandlerInterface) {
            $handler = app($handler);
        }

        $this->afterFormFields[$handler->getCodename()] = $handler;

        return $this;
    }

    public function formFields()
    {
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver", 'mysql');

        return collect($this->formFields)->filter(
            function ($after) use ($driver) {
                return $after->supports($driver);
            }
        );
    }
    /**
     * Is Facilitador handling the request?  Check if the current path is exactly "admin" or if
     * it contains admin/*
     *
     * @return bool
     */
    private $is_handling;

    public function handling()
    {
        if (! is_null($this->is_handling)) {
            return $this->is_handling;
        }
        if (env('DECOY_TESTING')) {
            return true;
        }
        $this->is_handling = preg_match('#^'.Config::get('application.routes.main').'($|/)'.'#i', Request::path());

        return $this->is_handling;
    }

    /**
     * Force Facilitador to believe that it's handling or not handling the request
     *
     * @param  bool $bool
     * @return void
     */
    public function forceHandling($bool)
    {
        $this->is_handling = $bool;
    }






    /**
     * Get the model class string from a controller class string
     *
     * @param  string $controller ex: "App\Http\Controllers\Admin\People"
     * @return string ex: "App\Person"
     */
    public function modelForController(string $controller): string
    {
        // Swap out the namespace if facilitador
        $model = str_replace(
            'Facilitador\Http\Controllers\Admin',
            'Facilitador\Models',
            $controller,
            $is_facilitador
        );
        $model = str_replace(
            'Support\Http\Controllers\Admin',
            'Support\Models',
            $model,
            $is_support
        );
        $model = str_replace(
            'Pedreiro\Http\Controllers\Admin',
            'Pedreiro\Models',
            $model,
            $is_support
        );
        $model = str_replace(
            'App\Http\Controllers\Admin',
            'App\Models',
            $model,
            $is_admin
        );
        $model = str_replace(
            'Http\Controllers\Admin',
            'Models',
            $model,
            $is_other
        );

        // Replace non-facilitador controller's with the standard model namespace
        if (! $is_facilitador && ! $is_support && ! $is_admin && ! $is_other) {
            $namespace = ucfirst(Config::get('application.routes.main'));
            $model = str_replace('App\Http\Controllers\\'.$namespace.'\\', 'App\\', $model);
        } else {
            $model = str_replace(
                'Controller',
                '',
                $model,
                $is_admin
            );
        }

        // Make it singular
        $offset = strrpos($model, '\\') + 1;

        return substr($model, 0, $offset).Str::singular(substr($model, $offset));
    }

    /**
     * Get the controller class string from a model class string
     *
     * @param  string $model ex: "App\Person"
     * @return string ex: "App\Http\Controllers\Admin\People"
     */
    public function controllerForModel(Model $model): Controller
    {
        // Swap out the namespace if facilitador
        $controller = str_replace('Facilitador\Models', 'Facilitador\Http\Controllers\Admin', $model, $is_facilitador);

        // Replace non-facilitador controller's with the standard model namespace
        if (! $is_facilitador) {
            $namespace = ucfirst(Config::get('application.routes.main'));
            $controller = str_replace('App\\', 'App\Http\Controllers\\'.$namespace.'\\', $controller);
        }

        // Make it plural
        $offset = strrpos($controller, '\\') + 1;

        return substr($controller, 0, $offset).Str::plural(substr($controller, $offset));
    }

    /**
     * Get the belongsTo relationship name given a model class name
     *
     * @param  string $model "App\SuperMan"
     * @return string "superMan"
     */
    public function belongsToName($model)
    {
        $reflection = new ReflectionClass($model);

        return lcfirst($reflection->getShortName());
    }

    /**
     * Get the belongsTo relationship name given a model class name
     *
     * @param  string $model "App\SuperMan"
     * @return string "superMen"
     */
    public function hasManyName($model)
    {
        return Str::plural($this->belongsToName($model));
    }

    /**
     * Get all input but filter out empty file fields. This prevents empty file
     * fields from overriding existing files on a model. Using this assumes that
     * we are filling a model and then validating the model attributes.
     *
     * @return array
     */
    public function filteredInput()
    {
        $files = $this->arrayFilterRecursive(Request::file());
        $input = array_replace_recursive(Request::input(), $files);

        return Library\Utils\Collection::nullEmpties($input);
    }

    /**
     * Run array_filter recursively on an array
     *
     * @link http://stackoverflow.com/a/6795671
     *
     * @param  array $array
     * @return array
     */
    protected function arrayFilterRecursive($array)
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = $this->arrayFilterRecursive($value);
            }
        }

        return array_filter($array);
    }


    public function getVersion()
    {
        return $this->version;
    }

    public function addAlert(Alert $alert)
    {
        $this->alerts[] = $alert;
    }

    public function alerts()
    {
        if (! $this->alertsCollected) {
            event(new AlertsCollection($this->alerts));

            $this->alertsCollected = true;
        }

        return $this->alerts;
    }

    protected function findVersion()
    {
        if (! is_null($this->version)) {
            return;
        }

        if ($this->filesystem->exists(base_path('composer.lock'))) {
            // Get the composer.lock file
            $file = json_decode(
                $this->filesystem->get(base_path('composer.lock'))
            );
            if (is_object($file)) {
                // Loop through all the packages and get the version of facilitador
                foreach ($file->packages as $package) {
                    if ($package->name == 'facilitador') {
                        $this->version = $package->version;

                        break;
                    }
                }
            }
        }
    }

    /**
     * @param string|Model|Collection $model
     *
     * @return bool
     */
    public function translatable($model)
    {
        if (! config('sitec.facilitador.multilingual.enabled')) {
            return false;
        }

        if (is_string($model)) {
            $model = app($model);
        }

        if ($model instanceof Collection) {
            $model = $model->first();
        }

        if (! is_subclass_of($model, Model::class)) {
            return false;
        }

        $traits = class_uses_recursive(get_class($model));

        return in_array(Translatable::class, $traits);
    }

    public function getLocales()
    {
        $appLocales = [];
        if ($this->filesystem->exists(resource_path('lang'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'facilitador'))) {
            $appLocales = array_diff(scandir(resource_path('lang'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'facilitador')), ['..', '.']);
        }

        $vendorLocales = array_diff(scandir(realpath(__DIR__.'/../publishes/lang')), ['..', '.']);
        $allLocales = array_merge($vendorLocales, $appLocales);

        asort($allLocales);

        return $allLocales;
    }




    /**
     * veio do decoy
     */

    /**
     * Generate title tags based on section content
     *
     * @return string
     */
    public function title()
    {
        // If no title has been set, try to figure it out based on breadcrumbs
        $title = View::yieldContent('title');
        if (empty($title)) {
            $title = app('rica.breadcrumbs')->title();
        }

        // Set the title
        $site = $this->site();

        return '<title>' . ($title ? "$title | $site" : $site) . '</title>';
    }
    /**
     * @todo Fazer DEscricao
     */
    public function description()
    {
        return 'description';
    }

    /**
     * Get the site name
     *
     * @return string
     */
    public function site()
    {
        $site = Config::get('sitec.site.name');
        if (is_callable($site)) {
            $site = call_user_func($site);
        }

        return $site;
    }

    /**
     * Add the controller and action as CSS classes on the body tag
     */
    public function bodyClass()
    {
        $path = Request::path();
        $classes = [];

        // Special condition for the elements
        if (strpos($path, '/elements/field/') !== false) {
            return 'elements field';
        }

        // Special condition for the reset page, which passes the token in as part of the route
        if (strpos($path, '/reset/') !== false) {
            return 'login reset';
        }

        // Tab-sidebar views support deep links that would normally affect the
        // class of the page.
        if (strpos($path, '/elements/') !== false) {
            return 'elements index';
        }

        // Get the controller and action from the URL
        preg_match('#/([a-z-]+)(?:/\d+)?(?:/(create|edit))?$#i', $path, $matches);
        $controller = empty($matches[1]) ? 'login' : $matches[1];
        $action = empty($matches[2]) ? 'index' : $matches[2];
        array_push($classes, $controller, $action);

        // Add the admin roles
        if ($admin = app('facilitador.user')) {
            $classes[] = 'role-'.$admin->role;
        }

        // Return the list of classes
        return implode(' ', $classes);
    }

    /**
     * Convert a key named with array syntax (i.e 'types[marquee][video]') to one
     * named with dot syntax (i.e. 'types.marquee.video]').  The latter is how fields
     * will be stored in the db
     *
     * @param  string $attribute
     * @return string
     */
    public function convertToDotSyntax($key)
    {
        return str_replace(['[', ']'], ['.', ''], $key);
    }

    /**
     * Do the reverse of convertKeyToDotSyntax()
     *
     * @param  string $attribute
     * @return string
     */
    public function convertToArraySyntax($key)
    {
        if (strpos($key, '.') === false) {
            return $key;
        }
        $key = str_replace('.', '][', $key);
        $key = preg_replace('#\]#', '', $key, 1);

        return $key.']';
    }

    /**
     * Formats the data in the standard list shared partial.
     * - $item - A row of data from a Model query
     * - $column - The field name that we're currently displaying
     * - $conver_dates - A string that matches one of the date_formats
     *
     * I tried very hard to get this code to be an aonoymous function that was passed
     * to the view by the view composer that handles the standard list, but PHP
     * wouldn't let me.
     */
    public function renderListColumn($item, $column, $convert_dates)
    {
        // Date formats
        $date_formats = [
            'date' => FORMAT_DATE,
            'datetime' => FORMAT_DATETIME,
            'time' => FORMAT_TIME,
        ];

        // Convert the item to an array so I can test for values
        $attributes = $item->getAttributes();

        // Get values needed for static array test
        $class = get_class($item);

        // If the column is named, locale, convert it to its label
        if ($column == 'locale') {
            $locales = Config::get('sitec.site.locales');
            if (isset($locales[$item->locale])) {
                return $locales[$item->locale];
            }

            // If the object has a method defined with the column value, use it
        } elseif (method_exists($item, $column)) {
            return call_user_func([$item, $column]);

        // Else if the column is a property, echo it
        } elseif (array_key_exists($column, $attributes)) {

            // Format date if appropriate
            if ($convert_dates && preg_match('/^\d{4}-\d{2}-\d{2}( \d{2}:\d{2}:\d{2})?$/', $item->$column)) {
                return date($date_formats[$convert_dates], strtotime($item->$column));

            // If the column name has a plural form as a static array or method on the model, use the key
                // against that array and pull the value.  This is designed to handle my convention
                // of setting the source for pulldowns, radios, and checkboxes as static arrays
                // on the model.
            } elseif (($plural = Str::plural($column))
                && (isset($class::$$plural) && is_array($class::$$plural) && ($ar = $class::$$plural)
                || (method_exists($class, $plural) && ($ar = forward_static_call([$class, $plural]))))
            ) {

                // Support comma delimited lists by splitting on commas before checking
                // if the key exists in the array
                return join(
                    ', ',
                    array_map(
                        function ($key) use ($ar, $class, $plural) {
                            if (array_key_exists($key, $ar)) {
                                return $ar[$key];
                            }

                            return $key;
                        },
                        explode(',', $item->$column)
                    )
                );

            // Just display the column value
            } else {
                return $item->$column;
            }
        }

        // Else, just display it as a string
        return $column;
    }

    /**
     * Get the value of an Element given it's key
     *
     * @param  string $key
     * @return mixed
     */
    public function el($key)
    {
        return app('facilitador.elements')->localize($this->locale())->get($key);
    }

    /**
     * Return a number of Element values at once in an associative array
     *
     * @param  string $prefix Any leading part of a key
     * @param  array  $crops  Assoc array with Element partial keys for ITS keys
     *                        and values as an arary of crop()-style arguments
     * @return array
     */
    public function els($prefix, $crops = [])
    {
        return app('facilitador.elements')
            ->localize($this->locale())
            ->getMany($prefix, $crops);
    }

    /**
     * Check if the Element key exists
     *
     * @param  string $key
     * @return bool
     */
    public function hasEl($key)
    {
        return app('facilitador.elements')
            ->localize($this->locale())
            ->hydrate()
            ->has($key);
    }


    /**
     * Set or return the current locale.  Default to the first key from
     * `support::site.locale`.
     *
     * @param  string $locale A key from the `support::site.locale` array
     * @return string
     */
    public function locale($locale = null)
    {
        // Set the locale if a valid local is passed
        if ($locale
            && ($locales = Config::get('sitec.site.locales'))
            && is_array($locales)
            && isset($locales[$locale])
        ) {
            return Session::put('locale', $locale);
        }

        // Return the current locale or default to first one.  Store it in a local var
        // so that multiple calls don't have to do any complicated work.  We're assuming
        // the locale won't change within a single request.
        if (! $this->locale) {
            $this->locale = Session::get('locale', $this->defaultLocale());
        }

        return $this->locale;
    }

    /**
     * Get the default locale, aka, the first locales array key
     *
     * @return string
     */
    public function defaultLocale()
    {
        if (($locales = Config::get('sitec.site.locales'))
            && is_array($locales)
        ) {
            reset($locales);

            return key($locales);
        }
    }

    
    /**
     * Set Influencia @todo tirar daqui
     */
    public function setInfluencia($influencia = false)
    {
        $this->influenciaModel = $influencia;
    }
    public function getInfluencia()
    {
        return $this->influenciaModel;
    }
    public function emptyInfluencia()
    {
        $this->setInfluencia();
    }







    /**
     * GAMBI @TODO
     */

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

            $this->addFormField("Pedreiro\\Elements\\FormFields\\{$class}");
        }
    }
}
