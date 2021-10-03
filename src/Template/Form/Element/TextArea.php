<?php

namespace Pedreiro\Template\Form\Element;

use PHPCensor\View;

class TextArea extends Text
{
    /**
     * @var int
     */
    protected $rows = 4;

    /**
     * @return int
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param int $rows
     *
     * @return void
     */
    public function setRows($rows): void
    {
        $this->rows = $rows;
    }

    /**
     * @param View $view
     *
     * @return void
     */
    protected function onPreRender(View &$view)
    {
        parent::onPreRender($view);

        $view->rows = $this->getRows();
    }
}
