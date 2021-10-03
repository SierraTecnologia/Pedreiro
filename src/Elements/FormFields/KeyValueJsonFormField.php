<?php

namespace Pedreiro\Elements\FormFields;

class KeyValueJsonFormField extends AbstractHandler
{
    protected $codename = 'key-value_to_json';

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view(
            'extended-fields::formfields.key_value_json',
            [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent,
            ]
        );
    }
}
