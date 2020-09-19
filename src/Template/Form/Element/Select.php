<?php

namespace Pedreiro\Template\Form\Element;

use Pedreiro\Template\Form\Input;
use PHPCensor\View;

class Select extends Input
{
    /**
     * @var array
     */
    protected $options = [];

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @param View $view
     */
    protected function onPreRender(View &$view)
    {
        parent::onPreRender($view);

        $view->options = $this->options;
    }
}
