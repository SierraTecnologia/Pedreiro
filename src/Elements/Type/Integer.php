<?php

declare(strict_types=1);

namespace Pedreiro\Elements\Type;

use Pedreiro\Models\Value;

/**
 * Pedreiro\Elements\Type\Integer.
 *
 * @property      int                                                $id
 * @property      int                                                $content
 * @property      int                                                $attribute_id
 * @property      int                                                $entity_id
 * @property      string                                             $entity_type
 * @property      \Carbon\Carbon|null                                $created_at
 * @property      \Carbon\Carbon|null                                $updated_at
 * @property-read \Pedreiro\Models\Attribute           $attribute
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $entity
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Pedreiro\Elements\Type\Integer whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pedreiro\Elements\Type\Integer whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pedreiro\Elements\Type\Integer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pedreiro\Elements\Type\Integer whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pedreiro\Elements\Type\Integer whereEntityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pedreiro\Elements\Type\Integer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pedreiro\Elements\Type\Integer whereUpdatedAt($value)
 * @mixin  \Eloquent
 */
class Integer extends Value
{
    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'content' => 'integer',
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

        $this->setTable(\Illuminate\Support\Facades\Config::get('sitec.attributes.tables.attribute_integer_values'));
        $this->setRules(
            [
            'content' => 'required|integer',
            'attribute_id' => 'required|integer|exists:'.\Illuminate\Support\Facades\Config::get('sitec.attributes.tables.attributes').',id',
            'entity_id' => 'required|integer',
            'entity_type' => 'required|string',
            ]
        );
    }
}
