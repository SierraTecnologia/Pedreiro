<?php

namespace Pedreiro\Elements\ContentTypes;

class Password extends BaseType
{
    /**
     * Handle password fields.
     *
     * @return null|string
     */
    public function handle(): ?string
    {
        return empty($this->request->input($this->row->field)) ? null :
            bcrypt($this->request->input($this->row->field));
    }
}
