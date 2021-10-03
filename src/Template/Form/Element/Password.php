<?php

namespace Pedreiro\Template\Form\Element;

use PHPCensor\View;

class Password extends Text
{
    /**
     * @param string $viewFile
     *
     * @return string
     */
    public function render($viewFile = null)
    {
        return parent::render(($viewFile ? $viewFile : 'Text'));
    }

    /**
     * @param View $view
     *
     * @return void
     */
    protected function onPreRender(View &$view)
    {
        parent::onPreRender($view);

        $view->type = 'password';
    }
}
