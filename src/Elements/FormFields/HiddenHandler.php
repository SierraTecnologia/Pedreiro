<?php

namespace Pedreiro\Elements\FormFields;

class HiddenHandler extends AbstractHandler
{
    protected $codename = 'hidden';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view(
            'support::shared.forms.fields.hidden', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
            ]
        );
    }
}
