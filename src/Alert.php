<?php

namespace Pedreiro;

use Pedreiro\Elements\Alert\ComponentInterface;

class Alert
{
    protected $components;

    protected $name;
    protected $type;

    public function __construct($name, $type = 'default')
    {
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * @return static
     */
    public function addComponent(ComponentInterface $component): self
    {
        $this->components[] = $component;

        return $this;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __call($name, $arguments)
    {
        $component = app('pedreiro.alert.components.'.$name, ['alert' => $this])
            ->setAlert($this);

        call_user_func_array([$component, 'create'], $arguments);

        return $this->addComponent($component);
    }
}
