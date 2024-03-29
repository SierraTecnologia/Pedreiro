<?php

namespace Pedreiro\Models;

use App;
// use Audit\Traits\Loggable;
// use Bkwld\Cloner\Cloneable;
use Muleta\Library\Utils\Collection;
use Bkwld\Upchuck\SupportsUploads;
use Config;
use DB;
use Event;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Log;
use Muleta\Traits\Models\Importable;
use Muleta\Traits\Models\ValidatingTrait;
use Muleta\Utils\Extratores\DbalExtractor;
use Muleta\Utils\Inclusores\DbalInclusor;

use Muleta\Utils\Mergeators\DbalMergeator;
use Muleta\Utils\Modificators\ArrayModificator;
use Pedreiro;
use Pedreiro\Collections\Base as BaseCollection;
use Pedreiro\Exceptions\Exception;
use Session;
use PedreiroURL;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use URL;

abstract class Base extends Model //Ardent
{
    /**
     * Esses primeiros veio do facilitador
     */
    /**
     * Adding common traits.  The memory usage of adding additional methods is
     * negligible.
     */
    // use Cloneable;
    use // SupportsUploads,
        \Muleta\Traits\Models\CanSerializeTransform,
        \Muleta\Traits\Models\Exportable;
    // Loggable;


    /**
     * @todo bug Resolver pra tirar esse coment
     * [2020-02-02 08:18:39] local.ERROR: SQLSTATE[42S22]: Column not found: 1054 Unknown column '2' in 'where clause' (SQL: select count(*) as aggregate from `users` where `email` = rafacollares@hotmail.com and `2` <> 2) {"exception":"[object] (Illuminate\\Database\\QueryExcept
    * ion(code: 42S22): SQLSTATE[42S22]: Column not found: 1054 Unknown column '2' in 'where clause' (SQL: select count(*) as aggregate from `users` where `email` = rafacollares@hotmail.com and `2` <> 2) at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Connecti
    * on.php:664, Doctrine\\DBAL\\Driver\\PDOException(code: 42S22): SQLSTATE[42S22]: Column not found: 1054 Unknown column '2' in 'where clause' at /var/www/html/vendor/doctrine/dbal/lib/Doctrine/DBAL/Driver/PDOConnection.php:63, PDOException(code: 42S22): SQLSTATE[42S22]: Col
    * umn not found: 1054 Unknown column '2' in 'where clause' at /var/www/html/vendor/doctrine/dbal/lib/Doctrine/DBAL/Driver/PDOConnection.php:61)
    * [stacktrace]
     */
    // use ValidatingTrait,
    use Importable;
    // use SortableTrait; // @todo Nem todos sao Sortable
    //---------------------------------------------------------------------------
    // Overrideable properties
    //---------------------------------------------------------------------------

    /**
     * This should be overridden by Models to store the array of their
     * Laravel validation rules
     *
     * @var array
     */
    public $rules = [

    ];

    /**
     * Should this model be localizable in the admin.  If not undefined, will
     * override the site config "auto_localize_root_models"
     *
     * @var bool
     */
    public static $localizable;

    /**
     * If false, this model cannot be cloned
     *
     * @var bool
     */
    public $cloneable = true;

    /**
     * Specify columns that shouldn't be duplicated by Bkwld\Cloner.  Include
     * slug by default so that Sluggable will automatically generate a new one.
     *
     * @var array
     */
    protected $clone_exempt_attributes = ['slug'];

    /**
     * Relations to follow when models are duplicated
     *
     * @var array
     */
    protected $cloneable_relations;

    /**
     * If populated, these will be used instead of the files that are found
     * automatically by getCloneableFileAttributes()
     *
     * @var array
     */
    protected $cloneable_file_attributes;

    /**
     * Should the model be exportable as CSV?
     *
     * @var bool
     */
    public $exportable = false;

    /**
     * If populated, these will ignore the override mutators in admin that are
     * in hasGetMutator() and hasSetMutator()
     *
     * @var array
     */
    protected $admin_mutators = [];



    /**
     * Should this model log it's changes.  Defaults to true if the change
     * happened while handling an admin request or via the console but not
     * during a non-http unit test.
     *
     * @param  string $action Like "deleted", "updated", etc
     * @return bool
     */
    public function shouldLogChange($action)
    {
        return Pedreiro::handling()
            || (App::runningInConsole() && request()->path() == '/');
    }

    //---------------------------------------------------------------------------
    // Instantiation
    //---------------------------------------------------------------------------



    /**
     * @todo Fazer
     */
    public function getIdentificador()
    {
        return $this->{$this->getKeyName()};
    }
    public function getId()
    {
        return $this->getIdentificador();
    }

    /**
     * No-Op callbacks invoked by Observers\ModelCallbacks.  These allow quick handling
     * of model event states.
     *
     * @return void
     */
    public function onSaving(): void
    {
    }
    public function onSaved(): void
    {
    }
    public function onCreating(): void
    {
    }
    public function onCreated(): void
    {
    }
    public function onUpdating(): void
    {
    }
    public function onUpdated(): void
    {
    }
    public function onDeleting(): void
    {
    }
    public function onDeleted(): void
    {
    }

    /**
     * Validation callback no-ops
     *
     * @param $validation Illuminate\Validation\Validator
     *
     * @return void
     */
    public function onValidating($validation)
    {
    }
    public function onValidated($validation): void
    {
    }

    /**
     * Many to many attach/detach callback no-ops
     *
     * @param $parent Eloquent\Model
     *
     * @return void
     */
    public function onAttaching($parent): void
    {
    }
    public function onAttached($parent): void
    {
    }
    public function onRemoving($parent): void
    {
    }
    public function onRemoved($parent): void
    {
    }

    /**
     * Check for a validation rule for a slug column
     *
     * @return bool
     */
    protected function needsSlugging()
    {
        return array_key_exists('slug', $this->rules);
    }

    public function getApresentationName()
    {
        return $this->{$this->getApresentationNameKey()};
    }

    public function getApresentationNameKey(): string
    {
        $atributesInOrderToDisplay = [
            'name',
            'description',
            'slug',
            'text',
            'token',
            'code',
            'id',
        ];
        $attributes = $this->getFillable();
        foreach ($atributesInOrderToDisplay as $display) {
            if (in_array($display, $attributes)) {
                return $display;
            }
        }

        return $this->getKeyName();
    }

    //---------------------------------------------------------------------------
    // Accessors
    //---------------------------------------------------------------------------

    /**
     * Return the title for the row for the purpose of displaying in admin list
     * views and breadcrumbs.  It looks for columns that are named like common
     * things that would be titles.
     *
     * @return string
     */
    public function getAdminTitleHtmlAttribute()
    {
        return $this->getAdminThumbTagAttribute().$this->getAdminTitleAttribute();
    }

    /**
     * Deduce the source for the title of the model and return that title
     *
     * @return array|null|string
     */
    public function getAdminTitleAttribute()
    {
        return implode(
            ' ',
            array_map(
                function ($attribute) {
                    return $this->$attribute;
                },
                $this->titleAttributes()
            )
        ) ?: __('pedreiro::base.untitled');
    }

    /**
     * Add a thumbnail img tag to the title
     *
     * @return null|string IMG tag
     */
    public function getAdminThumbTagAttribute()
    {
        if (! $url = $this->getAdminThumbAttribute()) {
            return;
        }

        return sprintf('<img src="%s" alt="">', $url);
    }

    /**
     * The URL for the thumbnail
     *
     * @return string URL
     */
    public function getAdminThumbAttribute($width = 40, $height = 40)
    {

        // Check if there are images for the model
        if (! method_exists($this, 'images')) {
            return;
        }

        $images = $this->images;
        if ($images->isEmpty()) {
            return;
        }

        // Get null-named (default) images first
        return $images->sortBy('name')->first()->crop($width, $height)->url;
    }

    /**
     * Get the URL sitemaps generated by Bkwld\SitemapFromRoutes
     *
     * @return string
     */
    public function getSitemapUrlAttribute()
    {
        return $this->uri;
    }

    /**
     * A no-op that should return the URI (an absolute path or a fulL URL) to the record
     *
     * @return void
     */
    public function getUriAttribute(): void
    {
    }

    /**
     * Get all file fields by looking at Upchuck config and validation rules
     *
     * @return array The keys of all the attributes that store file references
     */
    public function getFileAttributesAttribute()
    {

        // Get all the file validation rule keys
        $attributes = array_keys(
            array_filter(
                $this->rules,
                function ($rules) {
                    return preg_match('#file|image|mimes|video|dimensions#i', $rules);
                }
            )
        );

        // Get all the model attributes from upchuck
        if (method_exists($this, 'getUploadMap')) {
            $attributes = array_unique(
                array_merge(
                    $attributes,
                    array_values($this->getUploadMap())
                )
            );
        }

        // Return array of attributes
        return $attributes;
    }

    /**
     * Use getFileAttributesAttribute() to get the files that should be cloned
     * by Bkwld\Cloner
     *
     * @return array The keys of all the attributes that store file references
     */
    public function getCloneableFileAttributes()
    {
        if (isset($this->cloneable_file_attributes)) {
            return $this->cloneable_file_attributes;
        }

        return $this->getFileAttributesAttribute();
    }

    /**
     * Automatically add classes to rows in listing tables in the admin
     *
     * @return string
     */
    public function getAdminRowClassAttribute()
    {
        $classes = [];

        // Add a visbility classs
        if ($this->public) {
            $classes[] = 'is-public';
        }

        // Add a soft-deleted class
        if (method_exists($this, 'trashed') && $this->trashed()) {
            $classes[] = 'is-trashed';
        }

        // Return all classes
        return implode(' ', $classes);
    }

    /**
     * Expose model attributes for comparison by the localization sidebar
     *
     * @return array
     */
    public function getAttributesForLocalizationComparisonAttribute()
    {
        $attributes = $this->getAttributes();
        if (method_exists($this, 'croppedImages')) {
            $attributes['images'] = $this->croppedImages(300);
        }

        return $attributes;
    }

    //---------------------------------------------------------------------------
    // Listing view, action-column accessors
    //---------------------------------------------------------------------------

    /**
     * Make the markup for the actions column of the admin listing view.  The
     * indivudal actions are stored in an array that is iterted through in the
     * view
     *
     * @param  array $data The data passed to a listing view
     * @return array
     */
    public function makeAdminActions($data)
    {
        $actions = [];

        if ($html = $this->makeVisibilityAction($data)) {
            $actions['visibility'] = $html;
        }

        if ($html = $this->makeEditAction($data)) {
            $actions['edit'] = $html;
        }

        if ($html = $this->makeViewAction($data)) {
            $actions['view'] = $html;
        }

        if ($html = $this->makeDeleteAction($data)) {
            $actions['delete'] = $html;
        }

        return $actions;
    }

    /**
     * Find by the slug and fail if missing.  Invokes methods from the
     * Sluggable trait.
     *
     * @param  string $string
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public static function findBySlugOrFail(string $slug, array $columns = ['*'])
    {
        // Model not found, throw exception
        if (! $item = static::findBySlug($slug)) {
            throw (new ModelNotFoundException)->setModel(get_called_class());
        }

        // Return the model if visible
        $item->enforceVisibility();

        return $item;
    }

    //---------------------------------------------------------------------------
    // Utility methods
    //---------------------------------------------------------------------------

    /**
     * Throw exception if not public and no admin session
     *
     * @throws Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     *
     * @return void
     */
    public function enforceVisibility(): void
    {
        if (array_key_exists('public', $this->getAttributes())
            && ! $this->getAttribute('public')
            && ! app('facilitador.user')
        ) {
            throw new AccessDeniedHttpException;
        }
    }

    /**
     * Deduce the source for the title of the model
     *
     * @return array
     */
    public function titleAttributes()
    {
        // Convert to an array so I can test for the presence of values. As an
        // object, it would throw exceptions
        $row = $this->getAttributes();

        // Name before title to cover the case of people with job titles
        if (isset($row['name'])) {
            return ['name'];
        }

        // Search full names if people-type fields
        if (isset($row['first_name']) && isset($row['last_name'])) {
            return ['first_name', 'last_name'];
        }

        // Standard location for the title
        if (isset($row['title'])) {
            return ['title'];
        }

        // Default to no searchable attributes
        return [];
    }

    /**
     * The pivot_id may be accessible at $this->pivot->id if the result was fetched
     * through a relationship OR it may be named pivot_id out of convention (something
     * currently done in Facilitador_Base_Controller->get_index_child()).  This function
     * checks for either
     *
     * @return int
     */
    public function pivotId()
    {
        if (! empty($this->pivot->id)) {
            return $this->pivot->id;
        }

        if (! empty($this->pivot_id)) {
            return $this->pivot_id;
        }

        return null;
    }

    /**
     * Add a field to the blacklist
     *
     * @param string $field
     *
     * @return void
     */
    public function blacklist($field): void
    {
        $this->guarded[] = $field;
    }

    /**
     * Criado por mim posteriormente
     *
     * @return void
     */
    public function save(array $options = [])
    {
        parent::save($options);
    }

    /**
     *
     */
    public static function createAndAssociate($dataOrPrimaryCode, $associateTo)
    {
        return static::associate(static::createIfNotExistAndReturn($dataOrPrimaryCode), $associateTo);
    }

    /**
     * Associa ao Um Modelo ao Outro
     */
    public static function associate($associateFrom, $associateTo)
    {
        $method = Str::plural(Str::lower(\class_basename($associateFrom)));
        if (method_exists($associateTo, $method)) {
            return call_user_func_array([$associateTo, $method], [])->save($associateFrom);
        }

        return false;
    }

    public static function firstOrCreateAndAssociate(array $data, $associate = false): self
    {
        $entity = static::firstOrCreate($data);
        if ($associate) {
            static::associate($entity, $associate);
        }

        return $entity;
    }

    public static function createIfNotExistAndReturn($dataOrPrimaryCode)
    {
        if (!static::hasFeatureHability()) {
            dd('Nao tem feature');
        }

        $associate = false;
        // @todo migrar isso pra ca pro support
        if (config('siravel.influencia', false)) {
            $associate = Pedreiro::getInfluencia();
        }

        $modelFind = false;
        $keyName = (new static)->getKeyName();
        $data = ArrayModificator::convertToArrayWithIndex($dataOrPrimaryCode, $keyName);

        // Caso não tenho o Support ModelService
        if (! class_exists(\Support\Services\ModelService::class) || ! $eloquentEntityForModel = \Support\Services\ModelService::make(static::class)) {

            // Temp - Caso seja a 2 condicao da erro
            if (class_exists(\Support\Services\ModelService::class) && ! $eloquentEntityForModel) {
                dd('Nao deeria cair aqui debug');
            }

            return static::firstOrCreateAndAssociate($data, $associate);
        }

        $data = DbalInclusor::includeDataFromEloquentEntity($eloquentEntityForModel, $data, $keyName);

        /**
         * Procura somente usando os indices
         */
        $results = (DbalExtractor::generateWhereFromData(
            $data,
            $eloquentEntityForModel->getIndexes()
        ))->map(
            function ($query) use ($data) {
                if (is_array($query) && ! empty($query) && $modelFind = static::where($query)->first()) {
                    // @todo mesclar parametros e salvar
                    Log::debug('[Support] ModelBase -> Encontrado com tributos: '.print_r($query, true).' e Data: '.print_r($data, true));

                    return DbalMergeator::mergeWithAttributes($modelFind, $data);
                }

                return false;
            }
        )->reject(
            function ($result) {
                return ! $result;
            }
        );
        if ($results->isNotEmpty()) {
            $entity = $results->first();
            if ($associate) {
                static::associate($entity, $associate);
            }

            return $entity;
        }

        // PRocura por todos os parametros
        if ($modelFind = static::where($data)->first()) {
            $entity = $modelFind;
            if ($associate) {
                static::associate($entity, $associate);
            }

            return $entity;
        }

        return static::firstOrCreateAndAssociate($data, $associate);
    }



    /**
     * Help for Class
     *
     * @return bool
     */
    public function hasAttribute($attr): bool
    {
        return array_key_exists($attr, $this->attributes);
    }

    /**
     * Fiz pq tava quebrando @todo vericar, acho que vem do magento ou synfone
     */
    public function setModified($date)
    {
        return $date;
    }
    public function setCreated($date)
    {
        return $date;
    }






    /**
     * From Facilitador
     */


    /**
     * Use the Facilitador Base Collection
     *
     * @param array $models
     *
     * @return BaseCollection
     */
    public function newCollection(array $models = []): BaseCollection
    {
        return new BaseCollection($models);
    }

    /**
     * Disable mutators unless the active request isn't for the admin, the key
     * doesn't reference a true database-backed attribute, or the key was
     * expressly whitelisted in the admin_mutators property.
     *
     * @param  string $key
     * @return mixed
     */
    public function hasGetMutator($key)
    {
        if (! Pedreiro::handling()
            || ! array_key_exists($key, $this->attributes)
            || in_array($key, $this->admin_mutators)
        ) {
            return parent::hasGetMutator($key);
        }
    }

    /**
     * Disable mutators unless the active request isn't for the admin, the key
     * doesn't reference a true database-backed attribute, or the key was
     * expressly whitelisted in the admin_mutators property.
     *
     * @param  string $key
     * @return mixed
     */
    public function hasSetMutator($key)
    {
        if (! Pedreiro::handling()
            || ! array_key_exists($key, $this->attributes)
            || in_array($key, $this->admin_mutators)
        ) {
            return parent::hasSetMutator($key);
        }
    }

    //---------------------------------------------------------------------------
    // Instantiation
    //---------------------------------------------------------------------------

    /**
     * Constructor registers events and configures mass assignment
     */
    public function __construct(array $attributes = [])
    {
        /**
         * Facilitador
         */
        // Blacklist special columns that aren't intended for the DB
        $this->guarded = array_merge(
            $this->guarded,
            [
            'parent_controller', // Backbone.js sends this with sort updates
            'parent_id', // Backbone.js may also send this with sort
            'select-row', // This is the name of the checkboxes used for bulk delete
            ]
        );


        /**
         * Support
         */
        // Remove any settings that affect JSON conversion (visible / hidden) and
        // mass assignment protection (fillable / guarded) while in the admin
        if (Pedreiro::handling()) {
            $this->visible = $this->hidden = $this->fillable = $this->guarded = [];
        }

        // Continue Laravel construction
        parent::__construct($attributes);
    }

    //---------------------------------------------------------------------------
    // Slug creation via cviebrock/eloquent-sluggable
    //---------------------------------------------------------------------------

    /**
     * Tell sluggable where to get the source for the slug and apply other
     * customizations.
     *
     * @return array
     */
    public function sluggable(): array
    {
        if (! $this->needsSlugging()) {
            return [];
        }

        return [
            'slug' => [
                'source' => 'admin_title',
                'maxLength' => 100,
                'includeTrashed' => true,
            ],
        ];
    }

    /**
     * Make the visibility state action
     *
     * @param  array $data The data passed to a listing view
     * @return string
     */
    protected function makeVisibilityAction($data)
    {
        extract($data);

        // // Check if this model supports editing the visibility
        // if ($many_to_many
        //     || !app('facilitador.user')->can('publish', $controller)
        //     || !array_key_exists('public', $this->attributes)
        // ) {
        //     return;
        // }

        // Create the markup
        $public = $this->getAttribute('public');

        return sprintf(
            '<a class="visibility js-tooltip" data-placement="left" title="%s">
                <span class="glyphicon glyphicon-eye-%s"></span>
            </a>',
            $public ? __('pedreiro::base.standard_list.private') : __('pedreiro::base.standard_list.publish'),
            $public ? 'open' : 'close'
        );
    }

    /**
     * Make the edit or view action.
     *
     * @param  array $data The data passed to a listing view
     * @return string
     */
    protected function makeEditAction($data)
    {
        extract($data);

        // Make markup
        $editable = app('facilitador.user')->can('update', $controller);

        return sprintf(
            '<a href="%s" class="action-edit js-tooltip"
            data-placement="left" title="%s">
                <span class="glyphicon glyphicon-%s"></span>
            </a>',
            $this->getAdminEditUri($controller, $many_to_many), // URL
            $editable ? // Label
                __('pedreiro::base.action.edit') :
                __('pedreiro::base.action.read'),
            $editable ? 'pencil' : 'zoom-in' // Icon
        );
    }

    /**
     * Get the admin edit URL assuming you know the controller and whether it's
     * being listed as a many to many
     *
     * @param  string  $controller   ex: Admin\ArticlesController
     * @param  bool $many_to_many
     * @return string
     */
    public function getAdminEditUri($controller, $many_to_many = false)
    {
        if ($many_to_many) {
            return URL::to(PedreiroURL::action($controller.'@edit', $this->getKey()));
        }

        return URL::to(PedreiroURL::relative('edit', $this->getKey(), $controller));
    }

    /**
     * Make the view action
     *
     * @param array $data The data passed to a listing view
     *
     * @return null|string
     */
    protected function makeViewAction($data)
    {
        if (! $uri = $this->getUriAttribute()) {
            return;
        }

        return sprintf(
            '<a href="%s" target="_blank" class="action-view js-tooltip"
            data-placement="left" title="' . __('pedreiro::base.action.view') . '">
                <span class="glyphicon glyphicon-bookmark"></span>
            </a>',
            $uri
        );
    }

    /**
     * Make the delete action
     *
     * @param  array $data The data passed to a listing view
     * @return string
     */
    protected function makeDeleteAction($data)
    {
        extract($data);

        // @todo
        // // Check if this model can be deleted.  This mirrors code found in the table
        // //  partial for generating the edit link on the title
        // if (!(app('facilitador.user')->can('destroy', $controller)
        //     || ($many_to_many && app('facilitador.user')->can('update', $parent_controller)))
        // ) {
        //     return;
        // }

        // If soft deleted, show a disabled icon
        if (method_exists($this, 'trashed') && $this->trashed()) {
            return '<span class="glyphicon glyphicon-trash"></span>';
        }

        // Make the label
        $label = $many_to_many ?
            __('pedreiro::base.action.remove') :
            ($with_trashed ?
                __('pedreiro::base.action.soft_delete') :
                __('pedreiro::base.action.delete'));

        // Return markup
        return sprintf(
            '<a class="%s js-tooltip" data-placement="left" title="%s">
                <span class="glyphicon glyphicon-%s"></span>
            </a>',
            $many_to_many ? 'remove-now' : 'delete-now',
            $label,
            $many_to_many ? 'remove' : 'trash'
        );
    }

    //---------------------------------------------------------------------------
    // Scopes
    //---------------------------------------------------------------------------


    /**
     * Filter by the current locale
     *
     * @param  Illuminate\Database\Query\Builder $query
     * @param  string                            $locale
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeLocalize($query, $locale = null)
    {
        return $query->where('locale', $locale ?: Pedreiro::locale());
    }

    /**
     * Fire an Facilitador model event.
     *
     * @param  $string event The name of this event
     * @param  $array  args  An array of params that will be passed to the handler
     * @return object
     */
    public function fireFacilitadorEvent($event, $args = null)
    {
        $event = "Pedreiro::model.{$event}: ".get_class($this);

        return Event::dispatch($event, $args);
    }


    /**
     * Gatilhos para funcionar
     */

    /**
     * Gatinho para funcionar o ordered pra quem nao tem ordenacao
     */
    public function scopeOrderedForce($query)
    {
        if ($this->usesTimestamps()) {
            $query->orderBy($this->getTable().'.created_at', 'desc');
        }

        return $query;
    }

    /**
     * Orders instances of this model in the admin
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeOrdered(Builder $query, string $direction = 'asc'): Builder
    {
        if ($this->usesTimestamps()) {
            $query->orderBy($this->getTable().'.created_at', 'desc');
        }

        return $query;
    }

    /**
     * Verifica se é habilitado para esse sistema
     */
    public static function hasFeatureHability(): bool
    {
        if (isset(static::$features)) {
            return \Muleta\Modules\Features\Resources\FeatureHelper::hasActiveFeature(
                static::$features
            );
        }
        return true;
    }

}
