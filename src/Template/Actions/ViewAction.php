<?php

namespace Pedreiro\Template\Actions;

class ViewAction extends AbstractAction
{
    public function getTitle()
    {
        return __('facilitador::generic.view');
    }

    public function getIcon()
    {
        return 'facilitador-eye';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-warning float-right view',
        ];
    }

    public function getDefaultRoute()
    {
        return \Support\Routing\UrlGenerator::managerRoute($this->dataType->slug, 'show', $this->data->{$this->data->getKeyName()});
        // return route('facilitador.'.$this->dataType->slug.'.show', $this->data->{$this->data->getKeyName()});
    }
}
