<?php

namespace Pedreiro\Components;

use Support\Alert;

abstract class AbstractComponent implements ComponentInterface
{
    protected $alert;

    /**
     * @return static
     */
    public function setAlert(Alert $alert): self
    {
        $this->alert = $alert;

        return $this;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->alert, $name], $arguments);
    }
}
