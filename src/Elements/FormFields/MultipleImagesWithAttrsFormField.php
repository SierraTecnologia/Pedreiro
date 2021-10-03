<?php

namespace Pedreiro\Elements\FormFields;

class MultipleImagesWithAttrsFormField extends AbstractHandler
{
    protected $codename = 'multiple_images_with_attrs';

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view(
            'extended-fields::formfields.multiple_images_with_attrs',
            [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent,
            ]
        );
    }
}
