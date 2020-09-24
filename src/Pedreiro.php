<?php

namespace Pedreiro;

use Config;
use Pedreiro\Elements\FormFields\After\HandlerInterface as AfterHandlerInterface;
use Pedreiro\Elements\FormFields\HandlerInterface;
use Pedreiro\Template\Actions\DeleteAction;
use Pedreiro\Template\Actions\EditAction;
use Pedreiro\Template\Actions\RestoreAction;
use Pedreiro\Template\Actions\ViewAction;
use Request;

class Pedreiro
{
    protected $actions = [
        DeleteAction::class,
        RestoreAction::class,
        EditAction::class,
        ViewAction::class,
    ];
    
    protected $formFields = [];
    protected $afterFormFields = [];
    public function formField($row, $dataType, $dataTypeContent)
    {
        $formField = $this->formFields[$row->type];

        return $formField->handle($row, $dataType, $dataTypeContent);
    }

    public function afterFormFields($row, $dataType, $dataTypeContent)
    {
        return collect($this->afterFormFields)->filter(
            function ($after) use ($row, $dataType, $dataTypeContent) {
                return $after->visible($row, $dataType, $dataTypeContent, $row->details);
            }
        );
    }

    public function addFormField($handler)
    {
        if (! $handler instanceof HandlerInterface) {
            $handler = app($handler);
        }

        $this->formFields[$handler->getCodename()] = $handler;

        return $this;
    }

    public function addAfterFormField($handler)
    {
        if (! $handler instanceof AfterHandlerInterface) {
            $handler = app($handler);
        }

        $this->afterFormFields[$handler->getCodename()] = $handler;

        return $this;
    }

    public function formFields()
    {
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver", 'mysql');

        return collect($this->formFields)->filter(
            function ($after) use ($driver) {
                return $after->supports($driver);
            }
        );
    }
    /**
     * Is Facilitador handling the request?  Check if the current path is exactly "admin" or if
     * it contains admin/*
     *
     * @return bool
     */
    private $is_handling;

    public function handling()
    {
        if (! is_null($this->is_handling)) {
            return $this->is_handling;
        }
        if (env('DECOY_TESTING')) {
            return true;
        }
        $this->is_handling = preg_match('#^'.Config::get('application.routes.main').'($|/)'.'#i', Request::path());

        return $this->is_handling;
    }

    /**
     * Force Facilitador to believe that it's handling or not handling the request
     *
     * @param  bool $bool
     * @return void
     */
    public function forceHandling($bool)
    {
        $this->is_handling = $bool;
    }
}
