<?php

namespace Pedreiro\Components;

class BoxComponent extends AbstractComponent
{
    protected $color;
    protected $number;
    protected $name;
    protected $icon;
    protected $linkText;
    protected $link;

    public function __construct($color, $number, $name, $icon, $linkText, $link)
    {
        $this->color = $color;
        $this->number = $number;
        $this->name = $name;
        $this->icon = $icon;
        $this->linkText = $linkText;
        $this->link = $link;
    }

    public static function create($color, $number, $name, $icon, $linkText, $link): self
    {
        return new self($color, $number, $name, $icon, $linkText, $link);
    }

    /**
     * @return string
     */
    public function render()
    {
        return '<div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-'.$this->color.'">
            <div class="inner">
                <h3>'.$this->number.'</h3>
                <p>'.$this->name.'</p>
            </div>
            <div class="icon">
                <i class="'.$this->icon.'"></i>
            </div>
            <a href="'.$this->link.'" class="small-box-footer">'.$this->linkText.' <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>';
    }
}
