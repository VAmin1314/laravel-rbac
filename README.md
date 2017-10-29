### laravel-rbac
----------------
Role-based Permissions for Laravel 5.5+


#### Installation
----------------

1. In order to install Laravel 5 `laravel-rbac`, just add the following to your `composer.json`. Then run `composer update`:

        "gamelife/laravel-rbac":"dev-master"
   
   or run command in the console `composer require gamelife/laravel-rbac:dev-master`.

2. next, you should push config file, run command `php artisan vendor:publish --tag=config`.

   or you can run command `php artisan vendor:publish --provider=Gamelife\RBAC\RBACServiceProvider`.

3. If you haven't yet run `php artisan migrate` to generate use table, **you must do it**.

4. If you don't want to change the config file `config/rbac.php`, you can continue to run `php artisan make:migrate` .

5. When coming here, you have finished the installation.


#### RBAC Eloquent Model
-----------------------

1. Role
    
    you can use RBAC Role Model: `Gamelife\RBAC\Model\Role` or extend. you can do operations as below:
    
    - check role whether has some permissions
    
            Role::find(1)->hasPermissions('create-user,update-user');
        
        or 
        
            Role::find(1)->hasPermissions(['create-user', 'update-user']);

    - add some permissions
    
            Role::find(1)->attachPermissions(['create-user,update-user']);  # by permission name
 
            Role::find(1)->attachPermissions(1);                            # by permission id
            
            Role::find(1)->attachPermissions(Permission::find(1));          # by permission object
            
            Role::find(1)->attachPermissions(["id" => 2]);                  # an array of permission information.
            
            Role::find(1)->attachPermissions(['create-user', 1, Permission::find(1)]);  # or mix
    
    - remove som permissions by `detachPermissions` method and parameters as above.

2. Permission, you need to extend `Gamelife\RBAC\Model\Permission` to customize.

3. RBACUser, you can use trait`Gamelife\RBAC\Traits\RBACUser` in your `App\User` model;
  
        <?php
        
        namespace App;
        
        use Illuminate\Notifications\Notifiable;
        use Illuminate\Foundation\Auth\User as Authenticatable;
        use Gamelife\RBAC\Traits\RBACUser;
        
        class User extends Authenticatable
        {
            use Notifiable, RBACUser;
        }

    You can do some operations as below

    - get an array of all permissions
    
            $user->arrayPermissions();
            
    - check whether has roles;
    
            $user->hasRoles(['admin', 'common']);       # admin or common
            
            $user->hasRoles('admin,common');            # admin or common
            
            $user->hasRoles('admin,common', true);      # common and admin
    
    - check whether has permissions;
    
            $user->hasPermissions(['create-user', 'update-user']);        #  create-user or update-user
            
            $user->hasPermissions('update-user,create-user');             #  create-user or update-user
            
            $user->hasPermissions('update-user,create-user', true);       #  create-user and update-user
    
    - add roles for user;
    
            $user->attachRoles('admin');        # by role name.
            
            $user->attachRoles(1);              # by role id.
            
            $user->attachRoles(Role::find(1));  # by role object.
            
            $user->attachRoles(['id' => 1]);    # an array of role information.
            
            $user->attachRoles(['admin', 1, Role::find(1), ["id" => 2]]);
            
    - remove roles from user, `$user->detachRoles($roles)`,
    
    - add permissions for user;
    
            $user->attachPermissions('create-user');            # by permission name.
            
            $user->attachPermissions(1);                        # by permission id.
            
            $user->attachPermissions(Permission::find(1));      # by permission object.
            
            $user->attachPermissions(["id" => 2]);              # an array of permission infoomation.
            
            $user->attachPermissions(['create-user', 1, Permission::find(1), ["id" => 2]]);  
            
    - remove permissions from user, `$user->detachPermissions($permissions)`
    
    - ability, check a user whether has access to do something;
    
            $user->ability("admin,common", "create-user,update-user", [
                "validate_all" => false,             # false means or, true means and, default: false
                "return_type" => boolean|array|both, # return type, default boolean
            ]);
            
#### RBAC Middleware
--------------------

1. role
    
        Route::get('/user', 'UserController@index')->middlreware('role:admin|common');          # admin or common
        
        Route::get('/user', 'UserController@index')->middlreware('role:admin|common', true);    # true means admin and common

2. permission

        Route::post("/update/user", "UserController@update")
               ->middleware('permission:create-user|update-user');   # create-user or update-user
        
        Route::post("/update/user", "UserController@update")
                       ->middleware('permission:create-user|update-user', true);   # create-user and update-user
                       
3. ability

        Route::post('/update/user', 'UserController@update')
                ->middleware('ability:admin|common,create-user|update-user', true | false);


#### Access to RBAC
------------------  

        app()->make('rbac');
        
        app()->make('rbac')->hasRoles($roles);
        
        app()->make('rbac')->hasPermissions($roles);
        
        app()->make('rbac')->ability($roles, $permissions, [
        
            "validate_all" => false,
            
            "return_type" => boolean|array|both
        ]);

#### Blade Directive
-------------------

1. Role

        @role('admin,common')
            <p>admin</p>
        @endrole
        
        @role('admin,common', true)
             <p>admin</p>
        @endrole

2. permission
        
       @permission('create-user,update-user')
           <p>admin</p>
       @endpermission
       
       @permission('create-user,update-user', true)
            <p>admin</p>
       @endpermission

3. ability
        
       @ability('admin,common', 'create-user,update-user')
          <p>admin</p>
       @endability
      
       @ability('admin,common', 'create-user,update-user', ['validate_all' => true])
           <p>admin</p>
       @endability