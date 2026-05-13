<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        view()->composer('layouts.app', function ($view) {
            if (auth()->check()) {
                $userId = auth()->id();
                
                // Count requests where user is current approver
                $toApproveCount = \App\Models\RequestForm::where('status', 'pending')
                    ->whereHas('steps', function($q) use ($userId) {
                        $q->where('approver_id', $userId)
                          ->where('status', 'pending')
                          ->whereColumn('step_order', 'request_forms.current_step');
                    })->count();

                // Count requests where user needs to acknowledge
                $toAcknowledgeCount = \App\Models\RequestForm::where('user_id', $userId)
                    ->where('status', 'approved')
                    ->where('it_status', 'completed')
                    ->whereNull('user_acknowledged_at')
                    ->count();

                $view->with('navPendingCount', $toApproveCount + $toAcknowledgeCount);
            }
        });

        view()->composer('layouts.admin', function ($view) {
            if (auth()->check()) {
                $user = auth()->user();
                if ($user->role === 'admin' || $user->dept_id == 16) {
                    $newCount = \App\Models\RequestForm::where('status', 'pending')->count();
                    $itCount = \App\Models\RequestForm::where('status', 'approved')
                        ->where(function($q) {
                            $q->whereNull('it_status')->orWhere('it_status', '!=', 'completed');
                        })->count();
                        
                    $view->with('adminNewCount', $newCount);
                    $view->with('adminItCount', $itCount);
                    $view->with('adminTotalNotify', $newCount + $itCount);
                }
            }
        });
    }
}
