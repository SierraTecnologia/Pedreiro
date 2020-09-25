<?php

namespace Pedreiro;

use App\Models\User;
use Arrilot\Widgets\Facade as Widget;
use Bkwld\Library;
use Config;
use Crypto;
use Facilitador\Models\Menu;
use Facilitador\Models\MenuItem;
use Facilitador\Models\Permission;
use Facilitador\Models\Role;
use Facilitador\Models\Setting;
use Facilitador\Models\Translation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Pedreiro\Elements\FormFields\After\HandlerInterface as AfterHandlerInterface;
use Pedreiro\Elements\FormFields\HandlerInterface;
use Pedreiro\Template\Actions\DeleteAction;
use Pedreiro\Template\Actions\EditAction;
use Pedreiro\Template\Actions\RestoreAction;
use Pedreiro\Template\Actions\ViewAction;
use ReflectionClass;
use Request;
use Session;
use Siravel\Models\Blog\Category;
use Siravel\Models\Blog\Post;
use Siravel\Models\Negocios\Page;
use Support\Events\AlertsCollection;
use Support\Models\Application\DataRelationship;
use Support\Models\Application\DataRow;
use Support\Models\Application\DataType;
use Translation\Traits\HasTranslations;
use View;

class Pedreiro
{
    protected $actions = [
        DeleteAction::class,
        RestoreAction::class,
        EditAction::class,
        ViewAction::class,
    ];
    
    protected $formFields = [];
    protected $afterFormFields = [];
    public function formField($row, $dataType, $dataTypeContent)
    {
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
        if (!$is_facilitador && !$is_support && !$is_admin && !$is_other) {
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
        if (!$is_facilitador) {
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
}
