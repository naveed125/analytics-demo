<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\View\View;
use Segment\Segment;

class UserController extends BaseController
{
    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request) {

        $username = uniqid("user");

        if ($request->method() == 'GET') {

            // Track simple page view
            Segment::track([
                'event' => 'Page View'
            ]);

            return view('user.index', [
                'username' => $username,
                'errors' => null
            ]);
        }

        try {

            $username = trim($request->input('username'));
            $password = $request->input('password');

            $this->validate($request, [
                'username' => 'required|min:3',
                'password' => 'required|min:3',
                'agreement' => 'accepted'
            ]);
        }
        catch (ValidationException $exception) {
            return view('user.index', [
                'username' => $username,
                'errors' => collect($exception->errors())->values()->map(function ($item) {
                    return $item[0];
                })->implode(" ")
            ]);
        }

        $user = User::where('username', $username)->first();
        if (!$user) {
            $user = new User([
                'username' => $username,
                'password' => Hash::make($password)
            ]);
            $user->save();

            // Track user registration using Segment
            // https://segment.com/docs/connections/destinations/catalog/actions-google-analytics-4/#sign-up
            Segment::track([
                'event' => 'Signed Up',
                'userId' => $user->id,
                'properties' => [
                    'username' => $user->username
                ]
            ]);
        }
        else {
            if (!Hash::check($password, $user->password)) {
                return view('user.index', [
                    'username' => $username,
                    'errors' => 'Invalid password'
                ]);
            }
        }

        $session = new Session([
            'user_id' => $user->id,
            'token' => uniqid("", true),
            'expires' => Carbon::now()->addDays(7)
        ]);
        $session->save();

        // Track user login using Segment
        // https://segment.com/docs/connections/destinations/catalog/actions-google-analytics-4/#login
        Segment::track([
            'event' => 'Signed In',
            'userId' => $user->id,
            'properties' => [
                'username' => $user->username
            ]
        ]);

        return redirect("/bulletin?token={$session->token}");
    }

}
