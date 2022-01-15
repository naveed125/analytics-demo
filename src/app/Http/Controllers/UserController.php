<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\View\View;

class UserController extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request) {

        if ($request->method() == 'GET') {
            return view('user.index', [
                'username' => 'Guest',
                'errors' => null
            ]);
        }

        try {
            $this->validate($request, [
                'username' => 'required',
                'password' => 'required',
                'agreement' => 'accepted'
            ]);
        }
        catch (ValidationException $exception) {
            Log::error($exception);
            return view('user.index', [
                'username' => 'Guest',
                'errors' => collect($exception->errors())->values()->map(function ($item) {
                    return $item[0];
                })->implode(" ")
            ]);
        }

        return redirect("/user/dashboard?token=1234");
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
