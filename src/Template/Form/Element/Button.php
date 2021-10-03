<?php

namespace Pedreiro\Template\Form\Element;

use Pedreiro\Template\Form\Input;
use PHPCensor\View;

class Button extends Input
{
    /**
     * @return bool
     */
    public function validate()
    {
        return true;
    }

    /**
     * @param View $view
     *
     * @return void
     */
    protected function onPreRender(View &$view)
    {
        parent::onPreRender($view);

        $view->type = 'button';
    }
}
