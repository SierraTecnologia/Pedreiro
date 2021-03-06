<?php

namespace Pedreiro\Elements\ContentTypes;

class KeyValueJsonContentType extends BaseType
{
    /**
     * @return null|string
     */
    public function handle()
    {
        $value = $this->request->input($this->row->field);

        $new_parameters = [];
        foreach ($value as $key => $val) {
            if ($value[$key]['key']) {
                $new_parameters[] = $val;
            }
        }
        
        return json_encode($new_parameters);
    }
}
