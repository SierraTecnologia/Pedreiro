<?php

namespace Pedreiro\Template\Form\Element;

use Pedreiro\Template\Form\Input;
use PHPCensor\View;

class Checkbox extends Input
{
    /**
     * @var bool
     */
    protected $checked;

    /**
     * @var mixed
     */
    protected $checkedValue;

    /**
     * @return mixed
     */
    public function getCheckedValue()
    {
        return $this->checkedValue;
    }

    /**
     * @param mixed $value
     *
     * @return void
     */
    public function setCheckedValue($value): void
    {
        $this->checkedValue = $value;
    }

    /**
     * @param mixed $value
     *
     * @return void
     */
    public function setValue($value): void
    {
        if (is_bool($value) && $value === true) {
            $this->value = $this->getCheckedValue();
            $this->checked = true;

            return;
        }

        if ($value == $this->getCheckedValue()) {
            $this->value = $this->getCheckedValue();
            $this->checked = true;

            return;
        }

        $this->value = $value;
        $this->checked = false;
    }

    /**
     * @param View $view
     *
     * @return void
     */
    public function onPreRender(View &$view)
    {
        parent::onPreRender($view);

        $view->checkedValue = $this->getCheckedValue();
        $view->checked = $this->checked;
    }
}
