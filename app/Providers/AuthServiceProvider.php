<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use App\Models\AuthModel\User;
use App\Models\Role;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\AuthModel\Course' => 'App\Policies\CoursePolicy',
        'App\Models\AuthModel\Article' => 'App\Policies\ArticlePolicy',
        'App\Models\AuthModel\Comments' => 'App\Policies\CommentsPolicy',
        'App\Models\AuthModel\Todo' => 'App\Policies\TodoPolicy',
        'App\Models\AuthModel\User' => 'App\Policies\User'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('register_course', fn(User $user) => $user->user_rl_id === Role::IS_USER);
        Gate::define('unregister_course', fn(User $user) => $user->user_rl_id === Role::IS_USER);

        Gate::define('set_user_role', fn(User $user) => $user->user_rl_id === Role::IS_ADMIN);

        Gate::define('list_user_todos', function(User $user) {
            return $this->checkListUserEntitiesPermission($user->user_rl_id);
        });
        Gate::define('list_user_articles', function(User $user) {
            return $this->checkListUserEntitiesPermission($user->user_rl_id);
        });
        Gate::define('list_user_courses', function(User $user) {
            return $this->checkListUserEntitiesPermission($user->user_rl_id);
        });
    }

    /**
     * Function to get User entities permission
     *
     * @param [type] $user_role_id
     * @return void
     */
    private function checkListUserEntitiesPermission ($user_role_id) {
        switch(true) {
            case ($user_role_id === Role::IS_ADMIN) :
                return true; break;
            case ($user_role_id === Role::IS_USER) :
                return true; break;
            default :
                return false;
        }
    }
}
