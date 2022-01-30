<?php

namespace App\Providers;

use App\Models\Session;
use App\Models\Token;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Segment\Segment;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // The callback which receives the incoming request instance
        // should return either a User instance or null.
        $this->app['auth']->viaRequest('api', function ($request) {

            if ($request->has('token')) {

                $token = $request->input('token');
                $session = Session::where([
                    ['token', '=', $token],
                    ['expires', '>', Carbon::now()]
                ])->first();

                if (!$session) {
                    return null;
                }

                $user = User::find($session->user_id);
                if (!$user) {
                    return null;
                }

                Segment::identify([
                    'userId' => $user->id,
                    'traits' => [
                        'username' => $user->username
                    ]
                ]);
                return $user;
            }

            return null;
        });
    }
}
