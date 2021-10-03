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
     *
     * @return void
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    /**
     * @param View $view
     *
     * @return void
     */
    protected function onPreRender(View &$view)
    {
        parent::onPreRender($view);

        $view->options = $this->options;
    }
}
