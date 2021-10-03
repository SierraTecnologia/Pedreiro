<?php

namespace Pedreiro\Template\Actions;

class DeleteAction extends AbstractAction
{
    /**
     * @return array|null|string
     */
    public function getTitle()
    {
        return __('pedreiro::generic.delete');
    }

    /**
     * @return string
     *
     * @psalm-return 'facilitador-trash'
     */
    public function getIcon()
    {
        return 'facilitador-trash';
    }

    /**
     * @return string
     *
     * @psalm-return 'delete'
     */
    public function getPolicy()
    {
        return 'delete';
    }

    /**
     * @return (mixed|string)[]
     *
     * @psalm-return array{class: 'btn btn-sm btn-danger float-right delete', data-id: mixed, id: string}
     */
    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-danger float-right delete',
            'data-id' => $this->data->{$this->data->getKeyName()},
            'id' => 'delete-'.$this->data->{$this->data->getKeyName()},
        ];
    }

    /**
     * @return string
     *
     * @psalm-return 'javascript:;'
     */
    public function getDefaultRoute()
    {
        return 'javascript:;';
    }
}
