<?php

namespace App\Providers;

use App\Models\Lead;
use App\Models\Job;
use App\Models\User;
use App\Policies\LeadPolicy;
use App\Policies\JobPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Lead::class => LeadPolicy::class,
        Job::class  => JobPolicy::class,
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
