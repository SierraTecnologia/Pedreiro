<?php

namespace Pedreiro\Elements\FormFields;

class TextAreaHandler extends AbstractHandler
{
    protected $codename = 'text_area';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view(
            'pedreiro::shared.forms.fields.text_area',
            [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent,
            ]
        );
    }
}
