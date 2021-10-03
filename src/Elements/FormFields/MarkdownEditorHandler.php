<?php

namespace Pedreiro\Elements\FormFields;

class MarkdownEditorHandler extends AbstractHandler
{
    protected $codename = 'markdown_editor';

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view(
            'pedreiro::shared.forms.fields.markdown_editor',
            [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent,
            ]
        );
    }
}
