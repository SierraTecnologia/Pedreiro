<?php

namespace Pedreiro\Elements\FormFields;

class ImageHandler extends AbstractHandler
{
    protected $codename = 'image';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view(
            'pedreiro::shared.forms.fields.image',
            [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent,
            ]
        );
    }
}
