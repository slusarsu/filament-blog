<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Adm\Enums\RoleEnum;
use App\Models\Page;
use App\Models\Post;
use \Spatie\Permission\Models\Role as ModelRole;
use \Spatie\Permission\Models\Permission as ModelPermission;
use App\Models\Tag;
use App\Models\User;
use App\Policies\PagePolicy;
use App\Policies\PermissionPolicy;
use App\Policies\PostPolicy;
use App\Policies\RolePolicy;
use App\Policies\TagPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class,
        ModelRole::class => RolePolicy::class,
        ModelPermission::class => PermissionPolicy::class,
        Post::class => PostPolicy::class,
        Page::class => PagePolicy::class,
        Tag::class => TagPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            return $user->isAdmin();
        });
    }
}
