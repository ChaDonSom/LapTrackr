<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
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
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(5)->by($email . $request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // Only redirect to Filament login for explicit auth routes
        if (
            str_contains(request()->path(), 'login') ||
            str_contains(request()->path(), 'register') ||
            str_contains(request()->path(), 'forgot-password') ||
            str_contains(request()->path(), 'reset-password')
        ) {

            Fortify::loginView(function () {
                return redirect('/admin/login');
            });

            Fortify::registerView(function () {
                return redirect('/admin/register');
            });

            Fortify::requestPasswordResetLinkView(function () {
                return redirect('/admin/password-reset-request');
            });

            Fortify::resetPasswordView(function () {
                return redirect('/admin/password-reset');
            });
        }
    }
}
