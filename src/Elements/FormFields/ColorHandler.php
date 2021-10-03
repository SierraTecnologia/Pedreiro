<?php

namespace Pedreiro\Elements\FormFields;

class ColorHandler extends AbstractHandler
{
    protected $codename = 'color';

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view(
            'pedreiro::shared.forms.fields.color',
            [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent,
            ]
        );
    }
}
