<?php

namespace Gamelife\RBAC;

use Gamelife\RBAC\Middleware\Ability;
use Gamelife\RBAC\Middleware\Permission;
use Gamelife\RBAC\Middleware\Role;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class RBACServiceProvider extends ServiceProvider
{

    protected $routeMiddleware = [
        'role' => Role::class,
        'permission' => Permission::class,
        'ability' => Ability::class,
    ];

    protected $commands = [
        'Gamelife\RBAC\Console\PermissionGenerateCommand',
        'Gamelife\RBAC\Console\RoleCommand',
        'Gamelife\RBAC\Console\UserRoleCommand',
        'Gamelife\RBAC\Console\UserPermissionCommand',
        'Gamelife\RBAC\Console\RolePermissionCommand',
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/../../config/rbac.php' => config_path('rbac.php')], 'config');

        $this->loadMigrationsFrom(__DIR__ . '/../../database/');

        $this->bladeDirectives();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/rbac.php', 'rbac'
        );

        $this->registerRBAC();

        $this->registerMiddleware();

        $this->commands($this->commands);
    }

    public function registerRBAC()
    {

        $this->app->singleton('rbac', function($app){
            return new RBAC($app);
        });

    }

    public function registerMiddleware()
    {
        foreach ($this->routeMiddleware as $key => $middleware)
        {
            $this->app->make('router')->aliasMiddleware($key, $middleware);
        }
    }

    public function bladeDirectives()
    {
        Blade::directive('role', function ($expression) {
            return "<?php if(app()->make('rbac')->hasRoles($expression)): ?>";
        });

        Blade::directive('endrole', function ($expression){
            return "<?php endif; ?>";
        });

        Blade::directive('permission', function ($expression) {
            return "<?php if(app()->make('rbac')->hasPermissions($expression)): ?>";
        });

        Blade::directive('endpermission', function ($expression){
            return "<?php endif; ?>";
        });

        Blade::directive('ability', function ($expression) {
            return "<?php if(app()->make('rbac')->ability($expression)): ?>";
        });

        Blade::directive('endability', function ($expression){
            return "<?php endif; ?>";
        });
    }
}
