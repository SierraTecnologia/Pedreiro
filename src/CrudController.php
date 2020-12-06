<?php
/**
 * Arquivo destinado a criar o crud nos controllers
 */

namespace Pedreiro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

trait CrudController
{
    use \Muleta\Traits\Controllers\Exportable;
    use \Pedreiro\Traits\CrudModelUtilsTrait;
    use \Pedreiro\Traits\TemplateControllerTrait;

    /**
     * The model's relationships that the crud forms may need to use.
     *
     * @var array
     */
    protected $relationships = [];

    /**
     * The form's title (model name or description).
     *
     * @var string
     */
    protected $formTitle;

    /**
     * The base of the resource route.
     *
     * @var string
     */
    protected $route;

    /**
     * The blade layout that we extend.
     *
     * @var string
     */
    protected $bladeLayout = 'layouts.app';

    /**
     * Whether we want to handle deleted resources.
     *
     * @var bool
     */
    protected $withTrashed = false;

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = [];

    /**
     * Validation messages.
     *
     * @var array
     */
    protected $validationMessages = [];

    /**
     * Validation attributes nice names.
     *
     * @var array
     */
    protected $validationAttributes = [];

    /**
     * The model relations types that are eager loaded, or load data for options.
     *
     * @var array
     */
    protected $relationTypesToLoad = [
        'Illuminate\Database\Eloquent\Relations\BelongsToMany',
        'Illuminate\Database\Eloquent\Relations\BelongsTo',
    ];
    // ---------------------------
    // Resource Controller Methods
    // ---------------------------

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($this->withTrashed) {
            $entities = $this->model->withTrashed()->get();
        } else {
            $entities = $this->model->all();
        }

        $this->loadModelRelationships($entities);

        $fields = $this->getIndexFields();
        $title = $this->getFormTitle();
        $route = $this->getRoute();
        $withTrashed = $this->withTrashed;
        $bladeLayout = $this->bladeLayout;

        return view(
            'pedreiro::index',
            compact(
                'entities',
                'fields',
                'title',
                'route',
                'withTrashed',
                'bladeLayout'
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $entity = $this->model;

        $relationshipOptions = null;
        if (count($this->getRelationships())) {
            $relationshipOptions = $this->getModelRelationshipData();
        }

        $fields = $this->getFormFields();
        $title = $this->getFormTitle();
        $route = $this->getRoute();
        $bladeLayout = $this->bladeLayout;


        return view(
            'pedreiro::create',
            compact(
                'entity',
                'fields',
                'title',
                'route',
                'bladeLayout',
                'relationshipOptions'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Validator::make(
            $request->all(),
            $this->getValidationRules(),
            $this->getValidationMessages(),
            $this->getValidationAttributes()
        )->validate();

        $entity = $this->model->create($request->all());

        $this->syncModelRelationships($entity, $request);

        $request->session()->flash('status', 'Data saved successfully!');

        return redirect(route($this->getRoute().'.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $entity = $this->model->findOrFail($id);

        $this->loadModelRelationships($entity);

        $fields = $this->getFormFields();
        $title = $this->getFormTitle();
        $route = $this->getRoute();
        $bladeLayout = $this->bladeLayout;

        return view(
            'pedreiro::show',
            compact(
                'entity',
                'fields',
                'title',
                'route',
                'bladeLayout'
            )
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $entity = $this->model->findOrFail($id);

        $this->loadModelRelationships($entity);

        $relationshipOptions = null;
        if (count($this->getRelationships())) {
            $relationshipOptions = $this->getModelRelationshipData();
        }

        $fields = $this->getFormFields();
        $title = $this->getFormTitle();
        $route = $this->getRoute();
        $bladeLayout = $this->bladeLayout;

        return view(
            'pedreiro::edit',
            compact(
                'entity',
                'fields',
                'title',
                'route',
                'bladeLayout',
                'relationshipOptions'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        Validator::make(
            $request->all(),
            $this->getValidationRules(),
            $this->getValidationMessages(),
            $this->getValidationAttributes()
        )->validate();

        $entity = $this->model->findOrFail($id);

        // Handle checkboxes
        foreach ($this->getFormFields() as $field) {
            if ('checkbox' == $field['type']) {
                $request["{$field['name']}"] = ($request["{$field['name']}"]) ? true : false;
            }
        }

        $entity->update($request->all());

        $this->syncModelRelationships($entity, $request);

        $request->session()->flash('status', 'Data saved.');

        return redirect(route($this->getRoute().'.show', $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $entity = $this->model->findOrFail($id);

        $entity->delete();

        request()->session()->flash('status', 'Data deleted.');

        return redirect(route($this->getRoute().'.index'));
    }

    /**
     * Restore the specified softdeleted resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, $id)
    {
        $this->model->withTrashed()->where('id', $id)->restore();

        request()->session()->flash('status', 'Data restored.');

        return redirect(Str::before(request()->path(), "/$id/restore"));
    }

    /**
     * Get an array of all the model's relationships needed in the crud forms.
     *
     * @return array
     */
    public function getRelationships()
    {
        foreach ($this->getFormFields() as $field) {
            if (Arr::has($field, 'relationship')
                && ! Arr::has($this->relationships, $field['relationship'])
                && method_exists($this->model, $field['relationship'])
            ) {
                $this->relationships[] = $field['relationship'];
            }
        }

        return $this->relationships;
    }

    // --------------------------------
    // Getters & Setters
    // --------------------------------

    /**
     * Get an array of the defined validation rules.
     *
     * @return array
     */
    protected function getValidationRules()
    {
        return $this->validationRules ?: [];
    }

    /**
     * Get an array of the defined validation custom messages.
     *
     * @return array
     */
    protected function getValidationMessages()
    {
        return $this->validationMessages ?: [];
    }

    /**
     * Get an array of the defined validation attributes nice names.
     * These are used in the default Laravel validation messages.
     *
     * @return array
     */
    protected function getValidationAttributes()
    {
        $attributes = [];

        foreach ($this->getFormFields() as $field) {
            $attributes[$field['name']] = $field['label'];
        }

        return array_merge($attributes, $this->validationAttributes);
    }

    /**
     * Get the base route of the resource.
     *
     * @return string
     */
    protected function getRoute()
    {
        if ($this->route) {
            return $this->route;
        }

        // No route defined.
        // We find the full route from the request and get the base from there.
        $routeName = request()->route()->getName();

        return substr($routeName, 0, strrpos($routeName, '.'));
    }

    /**
     * Get the title of the resource to use in form headers.
     *
     * @return string
     */
    protected function getFormTitle()
    {
        if ($this->formTitle) {
            return $this->formTitle;
        }
        // No title defined. We return the model name.
        return Str::title(class_basename($this->model));
    }


    /**
     * Get an array of collections of related data.
     *
     * @return array array of collections
     */
    protected function getModelRelationshipData()
    {
        $formFields = $this->getFormFields();

        $relationships = $this->getRelationships();

        foreach ($relationships as $relationship) {
            // We need to find the relationship's field
            $field = Arr::first(
                array_filter(
                    $formFields,
                    function ($var) use ($relationship) {
                        if (Arr::has($var, 'relationship') && ($relationship == $var['relationship'])) {
                            return $var;
                        }
                    }
                )
            );

            if (in_array(get_class($this->model->$relationship()), $this->relationTypesToLoad, true)) {
                $relationshipData["$relationship"] = $this->model->$relationship()->getRelated()->all()->pluck($field['relFieldName'], 'id');
            }
        }

        return $relationshipData;
    }

    // --------------------------------
    // Helper methods
    // --------------------------------

    /**
     * Sync any BelongsToMany Relationships.
     *
     * @param Model   $model
     * @param Request $request
     */
    protected function syncModelRelationships(Model $model, Request $request)
    {
        $relationships = $this->getRelationships();

        foreach ($relationships as $relationship) {
            if ('Illuminate\Database\Eloquent\Relations\BelongsToMany' == get_class($model->$relationship())) {
                $model->$relationship()->sync($request->input($relationship, []));
            }
        }
    }

    /**
     * Eager load all the BelongsTo and BelongsToMany relationships.
     *
     * @param mixed $entities
     */
    protected function loadModelRelationships(&$entities)
    {
        $relationships = $this->getRelationships();

        foreach ($relationships as $relationship) {
            if (in_array(get_class($this->model->$relationship()), $this->relationTypesToLoad, true)) {
                $entities->load($relationship);
            }
        }
    }

    /**
     *  Check if a form field of a given name is defined.
     *
     * @param string $fieldName
     *
     * @return bool
     */
    protected function hasField($fieldName)
    {
        return in_array($fieldName, array_column($this->formFields, 'name'), true);
    }

    /**
     * EU Adaptei
     *
     * @param  [type] $model
     * @return void
     */

    /**
     * Get the permission options for the controller.  By default, these are the
     * stanadard CRUD actions
     *
     * @return array An associative array.  The keys are the permissions slugs.
     *               The value is either the description as a string or an array
     *               with the first index being an english title and the second
     *               being the description.
     */
    public function getPermissionOptions()
    {
        return [
            'read' => 'View listing and edit views',
            'create' => 'Create new items',
            'update' => 'Update existing items',
            'publish' => 'Move from "draft" to "published"',
            'destroy' => ['Delete', 'Delete items permanently'],
        ];
    }
    /**
     * Criei
     */

    protected function loadModel($model)
    {
        $this->model = $model;
        if (empty($this->indexFields)) {
            $this->indexFields = $this->model->indexFields;
        }
        if (empty($this->formFields)) {
            $this->formFields = $this->model->formFields;
        }
    }

    /**
     * Get the search settings for a controller, merging in default selectors
     *
     * @return array
     */
    // public function search(Request $request)
    public function search()
    {
        $search = new Search;

        return array_merge(
            $search->makeSoftDeletesCondition($this),
            $this->search ?: []
        );
    }


    /**
     * From Decoy @todo recuperar a merda q fiz
     */
    // rn $class::RULES;
    //     }
        
    //     if (!property_exists($class, 'rules')) {
    //         return [];
    //     }

    //     if (isset($class::$rules)) {
    //         return $class::$rules;
    //     }
    //     return (new $class)->rules;
    // }

    /**
     * All actions validate in basically the same way.  This is shared logic for that
     *
     * @param  BaseModel|Request|array $data
     * @param  array                   $rules    A Laravel rules array. If null, will be pulled from model
     * @param  array                   $messages Special error messages
     * @return void
     *
     * @throws ValidationFail
     */
    public function validateEloquentData($data, $rules = null, $messages = [])
    {
        // A request may be passed in when using Laravel traits, like how resetting
        // passwords work.  Get the input from it
        if (is_a($data, \Illuminate\Http\Request::class)) {
            $data = $data->input();
        }

        // Get validation rules from model
        $model = null;
        if (is_a($data, BaseModel::class)) {
            $model = $data;
            $data = $model->getAttributes();
            if (empty($rules)) {
                $rules = $model::$rules;
            }
        }

        // If an AJAX update, don't require all fields to be present. Pass just the
        // keys of the input to the array_only function to filter the rules list.
        if (Request::ajax() && Request::getMethod() == 'PUT') {
            $rules = array_only($rules, array_keys(request()->input()));
        }

        // Stop if no rules
        if (empty($rules)) {
            return;
        }

        // Build the validation instance and fire the intiating event.
        if ($model) {
            (new ModelValidator)->validateEloquentData($model, $rules, $messages);
        } else {
            $messages = array_merge(BkwldLibraryValidator::$messages, $messages);
            $validation = Validator::make($data, $rules, $messages);
            if ($validation->fails()) {
                throw new ValidationFail($validation);
            }
        }
    }

    /**
     * Format the results of a query in the format needed for the autocomplete
     * responses
     *
     * @param  array $results
     * @return array
     */
    public function formatAutocompleteResponse($results)
    {
        $output = [];
        foreach ($results as $row) {

            // Only keep the id and title fields
            $item = new stdClass;
            $item->id = $row->getKey();
            $item->title = $row->getAdminTitleAttribute();

            // Add properties for the columns mentioned in the list view within the
            // 'columns' property of this row in the response.  Use the same logic
            // found in Support::renderListColumn();
            $item->columns = [];
            foreach ($this->columns() as $column) {
                if (method_exists($row, $column)) {
                    $item->columns[$column] = call_user_func([$row, $column]);
                } elseif (isset($row->$column)) {
                    if (is_a($row->$column, 'Carbon\Carbon')) {
                        $item->columns[$column] = $row->$column->format(FORMAT_DATE);
                    } else {
                        $item->columns[$column] = $row->$column;
                    }
                } else {
                    $item->columns[$column] = null;
                }
            }

            // Add the item to the output
            $output[] = $item;
        }

        return $output;
    }

    // Return the per_page based on the input
    public function perPage()
    {
        $per_page = request('count', static::$per_page);
        if ($per_page == 'all') {
            return 1000;
        }

        return $per_page;
    }

    /**
     * Run the parent relationship function for the active model, returning the Relation
     * object. Returns false if none found.
     *
     * @return Illuminate\Database\Eloquent\Relations\Relation | false
     */
    private function parentRelation()
    {
        if ($this->parent && method_exists($this->parent, $this->parent_to_self)) {
            return $this->parent->{$this->parent_to_self}();
        }

        return false;
    }

    /**
     * Tell Laravel to look for view files within the app admin views so that,
     * on a controller-level basis, the app can customize elements of an admin
     * view through it's partials.
     *
     * @return void
     */
    protected function overrideViews()
    {
        $dir = Str::snake($this->controllerName());
        $path = base_path('resources/views/admin/').$dir;
        app('view')->prependNamespace('facilitador', $path);
    }

    /**
     * Creates a success message for CRUD commands
     *
     * @param  Support\Model\Base|string $title The model instance that is
     *                                          being worked on  or a string
     *                                          containing the title
     * @param  string                    $verb  Default: 'saved'. Past tense CRUD verb (created, saved, etc)
     * @return string                        The CRUD success message string
     */
    protected function successMessage($input = '', $verb = 'saved')
    {
        // Figure out the title and wrap it in quotes
        $title = $input;
        if (is_a($input, '\Pedreiro\Models\Base')) {
            $title = $input->getAdminTitleAttribute();
        }

        if ($title && is_string($title)) {
            $title = '"'.$title.'"';
        }

        // Render the message
        $message = __('pedreiro::base.success_message', ['model' => Str::singular($this->title), 'title' => $title, 'verb' => __("facilitador::base.verb.$verb")]);

        // Add extra messaging for copies
        if ($verb == 'duplicated') {
            $url = preg_replace('#/duplicate#', '/edit', Request::url());
            $message .= __('pedreiro::base.success_duplicated', ['url' => $url]);
        }

        // Add extra messaging if the creation was begun from the localize UI
        if ($verb == 'duplicated' && is_a($input, '\Pedreiro\Models\Base') && ! empty($input->locale)) {
            $message .= __('pedreiro::base.success_localized', ['locale' => \Illuminate\Support\Facades\Config::get('sitec.site.locales')[$input->locale]]);
        }

        // Return message
        return $message;
    }
}
