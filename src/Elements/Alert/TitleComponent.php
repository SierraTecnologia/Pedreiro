<?php

namespace Pedreiro\Elements\Alert;

class TitleComponent extends AbstractComponent
{
    protected $title;

    public function create($title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function render()
    {
        return "<strong>{$this->title}</strong>";
    }
}
