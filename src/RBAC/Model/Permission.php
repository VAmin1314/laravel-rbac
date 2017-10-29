<?php
/**
 * Created by PhpStorm.
 * User: fudenglong
 * Date: 2017/10/28
 * Time: 下午7:25
 */

namespace Gamelife\RBAC\Model;

use Illuminate\Database\Eloquent\Model;


class Permission extends Model
{
    public function __construct(array $attributes = [])
    {
        $this->table = config('rbac.table.permissions');

        parent::__construct($attributes);
    }

    /**
     * @param $permission string  role name;
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function getByName($permission)
    {
        return Permission::where('name', $permission)->first();
    }
}