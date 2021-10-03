<?php

namespace Pedreiro\Template\Actions;

class ViewAction extends AbstractAction
{
    /**
     * @return array|null|string
     */
    public function getTitle()
    {
        return __('pedreiro::generic.view');
    }

    /**
     * @return string
     *
     * @psalm-return 'facilitador-eye'
     */
    public function getIcon()
    {
        return 'facilitador-eye';
    }

    /**
     * @return string
     *
     * @psalm-return 'read'
     */
    public function getPolicy()
    {
        return 'read';
    }

    /**
     * @return string[]
     *
     * @psalm-return array{class: 'btn btn-sm btn-warning float-right view'}
     */
    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-warning float-right view',
        ];
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getDefaultRoute()
    {
        return \Pedreiro\Routing\UrlGenerator::managerRoute($this->dataType->slug, 'show', $this->data->{$this->data->getKeyName()});
    }
}
