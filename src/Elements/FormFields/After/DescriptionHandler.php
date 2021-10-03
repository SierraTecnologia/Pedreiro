<?php

namespace Pedreiro\Elements\FormFields\After;

class DescriptionHandler extends AbstractHandler
{
    protected $codename = 'description';

    /**
     * @return bool
     */
    public function visible($row, $dataType, $dataTypeContent, $options)
    {
        if (! isset($options->description)) {
            return false;
        }

        return ! empty($options->description);
    }

    /**
     * @return string
     */
    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return '<span class="glyphicon glyphicon-question-sign"
                                        aria-hidden="true"
                                        data-toggle="tooltip"
                                        data-placement="right"
                                        data-html="true"
                                        title="'.$options->description.'"></span>';
    }
}
