<?php

namespace Pedreiro\Elements\FormFields;

class CheckboxHandler extends AbstractHandler
{
    protected $codename = 'checkbox';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view(
            'support::shared.forms.fields.checkbox', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
            ]
        );
    }
}
