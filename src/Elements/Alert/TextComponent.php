<?php

namespace Pedreiro\Elements\Alert;

class TextComponent extends AbstractComponent
{
    protected $text;

    public function create($text): void
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function render()
    {
        return "<p>{$this->text}</p>";
    }
}
