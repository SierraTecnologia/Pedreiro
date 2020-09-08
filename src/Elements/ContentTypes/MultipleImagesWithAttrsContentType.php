<?php

namespace Pedreiro\Elements\ContentTypes;

use Pedreiro\Elements\ContentTypes\BaseType;
use Pedreiro\Elements\ContentTypes\MultipleImage;

class MultipleImagesWithAttrsContentType extends BaseType
{
    /**
     * @return string
     */
    public function handle()
    {
        $files = []; 
        if ($this->request->file($this->row->field)) {
            $pathes = (new MultipleImage($this->request, $this->slug, $this->row, $this->options))->handle();
            foreach (json_decode($pathes) as $i => $path) {
                $files[$i]['name'] = $path;
                $files[$i]['alt'] = '';
                $files[$i]['title'] = '';
            }
    
        }
        return json_encode($files);

    }
}
