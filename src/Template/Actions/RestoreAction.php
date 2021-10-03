<?php

namespace Pedreiro\Template\Actions;

class RestoreAction extends AbstractAction
{
    /**
     * @return array|null|string
     */
    public function getTitle()
    {
        return __('pedreiro::generic.restore');
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
     * @psalm-return 'restore'
     */
    public function getPolicy()
    {
        return 'restore';
    }

    /**
     * @return (mixed|string)[]
     *
     * @psalm-return array{class: 'btn btn-sm btn-success float-right restore', data-id: mixed, id: string}
     */
    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-success float-right restore',
            'data-id' => $this->data->{$this->data->getKeyName()},
            'id' => 'restore-'.$this->data->{$this->data->getKeyName()},
        ];
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getDefaultRoute()
    {
        return \Pedreiro\Routing\UrlGenerator::managerRoute($this->dataType->slug, 'restore', $this->data->{$this->data->getKeyName()});
        // return route('rica.facilitador.'.$this->dataType->slug.'.restore', $this->data->{$this->data->getKeyName()});
    }
}
