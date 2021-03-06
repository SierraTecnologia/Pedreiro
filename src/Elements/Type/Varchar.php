<?php

declare(strict_types=1);

namespace Pedreiro\Elements\Type;

use Pedreiro\Models\Value;

/**
 * Pedreiro\Elements\Type\Varchar.
 *
 * @property      int                                                $id
 * @property      string                                             $content
 * @property      int                                                $attribute_id
 * @property      int                                                $entity_id
 * @property      string                                             $entity_type
 * @property      \Carbon\Carbon|null                                $created_at
 * @property      \Carbon\Carbon|null                                $updated_at
 * @property-read \Pedreiro\Models\Attribute           $attribute
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $entity
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Pedreiro\Elements\Type\Varchar whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pedreiro\Elements\Type\Varchar whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pedreiro\Elements\Type\Varchar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pedreiro\Elements\Type\Varchar whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pedreiro\Elements\Type\Varchar whereEntityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pedreiro\Elements\Type\Varchar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pedreiro\Elements\Type\Varchar whereUpdatedAt($value)
 * @mixin  \Eloquent
 */
class Varchar extends Value
{
    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'content' => 'string',
        'attribute_id' => 'integer',
        'entity_id' => 'integer',
        'entity_type' => 'string',
    ];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(\Illuminate\Support\Facades\Config::get('sitec.attributes.tables.attribute_varchar_values'));
        $this->setRules(
            [
            'content' => 'required|string|max:150',
            'attribute_id' => 'required|integer|exists:'.\Illuminate\Support\Facades\Config::get('sitec.attributes.tables.attributes').',id',
            'entity_id' => 'required|integer',
            'entity_type' => 'required|string',
            ]
        );
    }
}
