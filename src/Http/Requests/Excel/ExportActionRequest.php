<?php

namespace Support\Http\Requests\Excel;

use Illuminate\Support\Collection;
use Laravel\Nova\Resource;
use Pedreiro\Elements\Fields\Field;

interface ExportActionRequest
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|mixed
     */
    public function toExportQuery();

    /**
     * @param \Laravel\Nova\Resource $resource
     *
     * @return array
     */
    public function indexFields(Resource $resource): array;

    /**
     * @param \Laravel\Nova\Resource $resource
     *
     * @return Collection|Field[]
     */
    public function resourceFields(Resource $resource): Collection;

    /**
     * @param string      $attribute
     * @param string|null $default
     *
     * @return string
     */
    public function findHeading(string $attribute, string $default = null): string;
}
