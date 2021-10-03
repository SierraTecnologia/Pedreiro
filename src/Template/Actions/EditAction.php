<?php

namespace Pedreiro\Template\Actions;

class EditAction extends AbstractAction
{
    /**
     * @return array|null|string
     */
    public function getTitle()
    {
        return __('pedreiro::generic.edit');
    }

    /**
     * @return string
     *
     * @psalm-return 'facilitador-edit'
     */
    public function getIcon()
    {
        return 'facilitador-edit';
    }

    /**
     * @return string
     *
     * @psalm-return 'edit'
     */
    public function getPolicy()
    {
        return 'edit';
    }

    /**
     * @return string[]
     *
     * @psalm-return array{class: 'btn btn-sm btn-primary float-right edit'}
     */
    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-primary float-right edit',
        ];
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getDefaultRoute()
    {
        return \Pedreiro\Routing\UrlGenerator::managerRoute($this->dataType->slug, 'edit', $this->data->{$this->data->getKeyName()});
        // return route('rica.facilitador.'.$this->dataType->slug.'.edit', $this->data->{$this->data->getKeyName()});
    }
}
