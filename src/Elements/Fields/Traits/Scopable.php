<?php

namespace Pedreiro\Elements\Fields\Traits;

/**
 * Save a scope
 */
trait Scopable
{
    /**
     * Preserve the scope
     *
     * @var callable
     */
    private $scope;

    /**
     * Allow the developer to customize the query for related items.  We'll execute the
     * scope function, passing it a reference to this query to customize
     *
     * @param callable $callback
     *
     * @return \Pedreiro\Elements\Fields\ManyToManyChecklist A field
     */
    public function scope($callback): self
    {
        if (is_callable($callback)) {
            $this->scope = $callback;
        }

        return $this;
    }
}
