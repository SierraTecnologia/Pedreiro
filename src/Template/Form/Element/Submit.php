<?php

namespace Pedreiro\Template\Form\Element;

use PHPCensor\View;

class Submit extends Button
{
    /**
     * @var string
     */
    protected $value = 'Submit';

    /**
     * @param string $viewFile
     *
     * @return string
     */
    public function render($viewFile = null)
    {
        return parent::render(($viewFile ? $viewFile : 'Button'));
    }

    /**
     * @param View $view
     *
     * @return void
     */
    protected function onPreRender(View &$view)
    {
        parent::onPreRender($view);

        $view->type = 'submit';
    }
}
