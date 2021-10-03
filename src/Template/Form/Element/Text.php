<?php

namespace Pedreiro\Template\Form\Element;

use Pedreiro\Template\Form\Input;
use PHPCensor\View;

class Text extends Input
{
    /**
     * @param View $view
     *
     * @return void
     */
    protected function onPreRender(View &$view)
    {
        parent::onPreRender($view);

        $view->type = 'text';
    }
}
