<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Policies\AppointmentPolicy;
use App\Policies\ServiceRequestPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        //User::class =>UserPolicy::class,
        Appointment::class => AppointmentPolicy::class,
        ServiceRequest::class => ServiceRequestPolicy::class,
    ];
}
