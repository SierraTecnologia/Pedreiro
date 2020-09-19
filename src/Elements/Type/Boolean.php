<?php

declare(strict_types=1);

namespace Pedreiro\Elements\Type;

use Pedreiro\Models\Value;

/**
 * Pedreiro\Elements\Type\Boolean.
 *
 * @property      int                                                $id
 * @property      bool                                               $content
 * @property      int                                                $attribute_id
 * @property      int                                                $entity_id
 * @property      string                                             $entity_type
 * @property      \Carbon\Carbon|null                                $created_at
 * @property      \Carbon\Carbon|null                                $updated_at
 * @property-read \Facilitador\Models\Attribute           $attribute
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $entity
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Pedreiro\Elements\Type\Boolean whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pedreiro\Elements\Type\Boolean whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pedreiro\Elements\Type\Boolean whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pedreiro\Elements\Type\Boolean whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pedreiro\Elements\Type\Boolean whereEntityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pedreiro\Elements\Type\Boolean whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pedreiro\Elements\Type\Boolean whereUpdatedAt($value)
 * @mixin  \Eloquent
 */
class Boolean extends Value
{
    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'content' => 'boolean',
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

        $this->setTable(\Illuminate\Support\Facades\Config::get('sitec.attributes.tables.attribute_boolean_values'));
        $this->setRules(
            [
            'content' => 'required|boolean',
            'attribute_id' => 'required|integer|exists:'.\Illuminate\Support\Facades\Config::get('sitec.attributes.tables.attributes').',id',
            'entity_id' => 'required|integer',
            'entity_type' => 'required|string',
            ]
        );
    }
}
