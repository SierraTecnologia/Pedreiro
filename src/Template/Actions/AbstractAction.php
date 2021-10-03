<?php

namespace Pedreiro\Template\Actions;

abstract class AbstractAction implements ActionInterface
{
    protected $dataType;
    protected $data;

    public function __construct($dataType, $data)
    {
        $this->dataType = $dataType;
        $this->data = $data;
    }

    /**
     * @return void
     */
    public function getDataType()
    {
    }

    /**
     * @return void
     */
    public function getPolicy()
    {
    }

    public function getRoute($key)
    {
        if (method_exists($this, $method = 'get'.ucfirst($key).'Route')) {
            return $this->$method();
        } else {
            return $this->getDefaultRoute();
        }
    }

    /**
     * @return array
     *
     * @psalm-return array<empty, empty>
     */
    public function getAttributes()
    {
        return [];
    }

    public function convertAttributesToHtml(): string
    {
        $result = '';

        foreach ($this->getAttributes() as $key => $attribute) {
            $result .= $key.'="'.$attribute.'"';
        }

        return $result;
    }

    public function shouldActionDisplayOnDataType(): bool
    {
        return $this->dataType->name === $this->getDataType() || $this->getDataType() === null;
    }
}
