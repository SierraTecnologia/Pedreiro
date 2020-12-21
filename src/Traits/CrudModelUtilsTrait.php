<?php

namespace Pedreiro\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

trait CrudModelUtilsTrait
{
    /**
     * The model that we use.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * The fields shown on the index page.
     *
     * @var array
     */
    protected $indexFields = [];

    /**
     * The fields shown in forms as an array of arrays.
     * Each field is an array with keys:
     * name, label, type, relationship (if applicable).
     * Type can be: text, textarea, email, url, password, date, select, select_multiple, checkbox, radio.
     *
     * @var array
     */
    public $formFields = [];
    /**
     * Get the array of fields that we need to present in the resource index.
     *
     * @return array
     */
    protected function getIndexFields()
    {
        if (empty($this->indexFields)) {
            $this->indexFields = $this->model->indexFields;
        }

        // If none declared, use the first of the formFields.
        if (! $this->indexFields || 0 == count($this->indexFields)) {
            $this->indexFields = [$this->getFormFields()[0]['name']];

            return array_slice($this->getFormFields(), 0, 1);
        }

        return Arr::where(
            $this->getFormFields(),
            function ($value) {
                return in_array($value['name'], $this->indexFields, true);
            }
        );
    }

    /**
     * Get the array of fields that we need to present in the forms.
     *
     * @return array
     */
    public function getFormFields()
    {
        if (empty($this->formFields)) {
            $this->formFields = $this->model->formFields;
        }


        // No fields declared. We have a table with only a name field.
        if (! $this->formFields || 0 == count($this->formFields)) {
            if (! is_array($this->formFields)) {
                return $this->formFields = [['name' => 'name', 'label' => 'Name', 'type' => 'text']];
            }
            array_push($this->formFields, ['name' => 'name', 'label' => 'Name', 'type' => 'text']);

            return $this->formFields;
        }

        foreach ($this->formFields as $key => $field) {
            if (Arr::has($field, 'relationship') && $field['type'] !== "inline" && ! Arr::has($field, 'relFieldName')) {
                // set default name of related table main field
                $this->formFields[$key]['relFieldName'] = 'name';
            }
            // @todo fazer para type inine
            //  else if (Arr::has($field, 'relationship') && $field['type'] === "inline") {
            //     // set default name of related table main field
            //     $this->formFields[$key]['relFieldName'] = 'name';
            // }
        }

        return $this->formFields;
    }

    public function getElementFromIndexFields($element)
    {
        return (new Collection($this->getIndexFields()))->map(
            function ($column) use ($element) {
                return $column[$element];
            }
        );
    }
}
