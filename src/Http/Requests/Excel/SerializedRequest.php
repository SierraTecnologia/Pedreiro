<?php

namespace Pedreiro\Http\Requests\Excel;

use Laravel\Nova\Http\Requests\ActionRequest;
use Laravel\Nova\Http\Requests\LensActionRequest;
use Support\Http\Requests\FacilitadorRequest as NovaRequest;

class SerializedRequest
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $resource;

    /**
     * @var null|string
     */
    private $lens;

    /**
     * @param string      $className
     * @param string      $resource
     * @param string|null $lens
     */
    public function __construct(string $className, string $resource, string $lens = null)
    {
        $this->className = $className;
        $this->resource = $resource;
        $this->lens = $lens;
    }

    /**
     * @param ActionRequest $request
     *
     * @return SerializedRequest
     */
    public static function serialize(ActionRequest $request)
    {
        return new static(
            get_class($request),
            $request->resource,
            $request->lens
        );
    }

    /**
     * @return NovaRequest|ExportActionRequest
     */
    public function unserialize()
    {
        $className = $this->className;

        /**
         * @var ExportActionRequest|NovaRequest $request
        */
        $request = new $className;
        $request->resource = $this->resource;
        $request->lens = $this->lens;

        return $request;
    }
}
