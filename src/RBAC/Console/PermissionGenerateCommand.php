<?php
/**
 * Created by PhpStorm.
 * User: fudenglong
 * Date: 2017/10/30
 * Time: 下午9:23
 */

namespace Gamelife\RBAC\Console;

use Gamelife\RBAC\Model\Permission;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Console\RouteListCommand;

class PermissionGenerateCommand extends RouteListCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'permission:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'laravel-rbac permission quickly operation';

    /**
     * An array of all the registered routes.
     *
     * @var \Illuminate\Routing\RouteCollection
     */
    protected $routes;

    /**
     * The route service.
     *
     * @var Router
     */
    protected $router;


    /**
     * PermissionCommand constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        parent::__construct($router);
    }

    public function handle()
    {
        $permissions = $this->getPermissions();
        $this->table(['name', 'slug', 'http_method', 'http_path'], $permissions);

        if ($this->confirm('Confirm to generate permissions?')) {
            foreach ($permissions as $permission) {
                if (!Permission::getByName($permission['name'])) {
                    $model = new Permission();
                    $model->name = $permission['name'];
                    $model->slug = $permission['slug'];
                    $model->http_path = $permission['http_path'];
                    $model->http_method = $permission['http_method'];
                    $model->save();
                }
            }
        }
    }

    public function getPermissions()
    {
        $permissions = [];
        foreach ($this->getRoutes() as $route)
        {
            $permissions[] = [
                'name' => "[${route['method']}]${route['uri']}",
                'slug' => $route['name'] ?? '',
                'http_method' => $route['method'],
                'http_path' => $route['uri']
            ];
        }

        return $permissions;
    }
}