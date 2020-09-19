<?php

namespace Pedreiro\Template\Form\Validator;

interface ValidatorInterface
{
    public function __invoke($value);
}
