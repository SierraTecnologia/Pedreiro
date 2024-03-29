<?php

namespace Pedreiro\Elements\Fields\Traits;

/**
 * Store the raw label text since Former immediately transforms it
 * with HTML
 */
trait CaptureLabel
{
    /**
     * Preserve the label text
     *
     * @var callable
     */
    private $label_text;

    /**
     * Override the parent label so we can use the raw text of the label
     *
     * @param string $text       A label
     * @param array  $attributes The label's attributes
     *
     * @return \Former\Traits\Field A field
     */
    public function label($text, $attributes = []): \Former\Traits\Field
    {
        $this->label_text = $text;

        return parent::label($text, $attributes);
    }
}
