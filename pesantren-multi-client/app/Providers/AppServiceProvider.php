<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\{Student, Bill, LeaveRequest, Attendance};
use App\Policies\{StudentPolicy, BillPolicy, LeaveRequestPolicy, AttendancePolicy};
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
public function register(): void
{
    $this->app->scoped(
        \App\Support\ClientContext::class,
        fn (): \App\Support\ClientContext => new \App\Support\ClientContext,
    );
}
public function boot(): void
{
    Gate::policy(Student::class, StudentPolicy::class);
    Gate::policy(Bill::class, BillPolicy::class);
    Gate::policy(LeaveRequest::class, LeaveRequestPolicy::class);
    Gate::policy(Attendance::class, AttendancePolicy::class);
}
}
