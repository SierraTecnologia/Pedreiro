<?php

namespace Pedreiro\Template\Actions;

class DeleteAction extends AbstractAction
{
    public function getTitle()
    {
        return __('pedreiro::generic.delete');
    }

    public function getIcon()
    {
        return 'facilitador-trash';
    }

    public function getPolicy()
    {
        return 'delete';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-danger float-right delete',
            'data-id' => $this->data->{$this->data->getKeyName()},
            'id' => 'delete-'.$this->data->{$this->data->getKeyName()},
        ];
    }

    public function getDefaultRoute()
    {
        return 'javascript:;';
    }
}
