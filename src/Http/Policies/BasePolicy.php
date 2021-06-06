<?php

namespace Pedreiro\Http\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Pedreiro;
use Porteiro\Contracts\User;

class BasePolicy
{
    use HandlesAuthorization;

    protected static $datatypes = [];


    public function __construct()
    {
        dd('Polyci: aqui foi2 ');
    }
    
    /**
     * Handle all requested permission checks.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return bool
     */
    public function __call($name, $arguments)
    {
        dd('Polyci: aqui foi ');
        if (count($arguments) < 2) {
            throw new \InvalidArgumentException('not enough arguments');
        }
        /**
 * @var \Porteiro\Contracts\User $user
*/
        $user = $arguments[0];

        /**
 * @var $model
*/
        $model = $arguments[1];

        return $this->checkPermission($user, $model, $name);
    }

    /**
     * Check if user has an associated permission.
     *
     * @param \Porteiro\Contracts\User $user
     * @param object                      $model
     * @param string                      $action
     *
     * @return bool
     */
    protected function checkPermission(User $user, $model, $action)
    {
        if (! isset(self::$datatypes[get_class($model)])) {
            $dataType = Pedreiro::model('DataType');
            self::$datatypes[get_class($model)] = $dataType->where('model_name', get_class($model))->first();
        }

        $dataType = self::$datatypes[get_class($model)];

        return $user->hasPermission($action.'_'.$dataType->name);
    }
}
