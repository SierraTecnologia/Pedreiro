<?php

namespace Pedreiro\Template\Actions;

class EditAction extends AbstractAction
{
    public function getTitle()
    {
        return __('pedreiro::generic.edit');
    }

    public function getIcon()
    {
        return 'facilitador-edit';
    }

    public function getPolicy()
    {
        return 'edit';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-primary float-right edit',
        ];
    }

    public function getDefaultRoute()
    {
        return \Pedreiro\Routing\UrlGenerator::managerRoute($this->dataType->slug, 'edit', $this->data->{$this->data->getKeyName()});
        // return route('rica.facilitador.'.$this->dataType->slug.'.edit', $this->data->{$this->data->getKeyName()});
    }
}
