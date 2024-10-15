<?php

namespace App\Providers;

use App\Models\Team;
use App\Models\User;
use App\Policies\RolePolicy;
use App\Policies\TeamPolicy;
use Filament\Facades\Filament;
use App\Observers\UserObserver;
use Filament\Support\Assets\Js;
use Filament\Support\Assets\Css;
use App\Policies\PermissionPolicy;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentAsset;
use Spatie\Permission\Contracts\Permission;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        User::observe(UserObserver::class);
        Gate::before(function (User $user, string $ability) {

            // dd($user->isSuperAdmin());
            // dd(Auth::user()->getRoleNames());

            return $user->isSuperAdmin() ? true : null;
            // return true;
        });
        Gate::policy(Team::class, TeamPolicy::class);
        // Gate::policy(Permission::class, PermissionPolicy::class);
        FilamentAsset::register([
            Css::make('ckeditor', 'https://cdn.ckeditor.com/ckeditor5/43.1.0/ckeditor5.css'),
        ]);
    }
}
