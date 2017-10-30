<?php
/**
 * Created by PhpStorm.
 * User: fudenglong
 * Date: 2017/10/30
 * Time: 下午9:10
 */

namespace Gamelife\RBAC;

use Illuminate\Support\Facades\Facade;


class RBACFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'rbac';
    }
}