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

class UserController extends BaseController
{
    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request) {

        $username = uniqid("user");

        if ($request->method() == 'GET') {
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
            Log::info("CREATING NEW USER: {$username}");
            $user = new User([
                'username' => $username,
                'password' => Hash::make($password)
            ]);
            $user->save();
        }

        if (Hash::check($user->password, $password)) {
            return view('user.index', [
                'username' => $username,
                'errors' => 'Invalid password'
            ]);
        }

        $session = new Session([
            'user_id' => $user->id,
            'token' => uniqid("", true),
            'expires' => Carbon::now()->addDays(7)
        ]);
        $session->save();

        return redirect("/user/dashboard?token={$session->token}");
    }

    /**
     * @param Request $request
     * @return View
     */
    public function dashboard(Request $request) {
        // TODO REAL DASHBOARD COMES HERE
        return view('user.index', [
            'username' => 'Guest',
            'errors' => null
        ]);
    }
}
