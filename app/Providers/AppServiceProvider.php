<?php

namespace App\Providers;

use App\Models\File;
use App\Models\Space;
use App\Policies\FilePolicy;
use App\Policies\SpacePolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Space::class, SpacePolicy::class);
        Gate::policy(File::class, FilePolicy::class);
        Paginator::useTailwind();
    }
}
