<?php

namespace Pedreiro\Elements\Alert;

class ButtonComponent extends AbstractComponent
{
    protected $text;
    protected $link;
    protected $style;

    public function create($text, $link = '#', $style = 'default'): void
    {
        $this->text = $text;
        $this->link = $link;
        $this->style = $style;
    }

    /**
     * @return string
     */
    public function render()
    {
        return "<a href='{$this->link}' class='btn btn-{$this->style}'>{$this->text}</a>";
    }
}
