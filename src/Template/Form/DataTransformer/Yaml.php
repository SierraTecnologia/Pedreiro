<?php
namespace Pedreiro\Template\Form\DataTransformer;

class Yaml implements DataTransformerInterface
{
    public function transform($value)
    {
        /* nothing to do here - only called before displaying values on FE */
        return $value;
    }

    /**
     * @return string
     */
    public function reverseTransform($value)
    {
        return str_replace("\t", "    ", $value);
    }
}
