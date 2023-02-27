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

        Gate::define('list_user_todo', function(User $user) {
            $user->user_rl_id === (Role::IS_ADMIN || Role::IS_USER);
        });
        Gate::define('list_user_article', function(User $user) {
            $user->user_rl_id === (Role::IS_ADMIN || Role::IS_USER);
        });
        Gate::define('list_user_courses', function(User $user) {
            $user->user_rl_id === (Role::IS_ADMIN || Role::IS_USER);
        });
    }
}
