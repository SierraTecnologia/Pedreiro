<?php

namespace Pedreiro\Elements\FormFields\After;

use Muleta\Traits\Models\Renderable;

abstract class AbstractHandler implements HandlerInterface
{
    use Renderable;

    /**
     * @return true
     */
    public function visible($row, $dataType, $dataTypeContent, $options)
    {
        return true;
    }

    public function handle($row, $dataType, $dataTypeContent)
    {
        $content = $this->createContent(
            $row,
            $dataType,
            $dataTypeContent,
            $row->details
        );

        return $this->render($content);
    }

    public function getCodename()
    {
        return $this->codename;
    }
}
