<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Session;
use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\View\View;
use Segment\Segment;

class BulletinController extends BaseController
{
    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request) {

        $errors = null;
        $user = Auth::user();

        if ($request->method() == 'POST') {
            try {
                $this->validate($request, [
                    'message' => 'required|min:3'
                ]);
            }
            catch (ValidationException $exception) {
                $errors = collect($exception->errors())->values()->map(function ($item) {
                    return $item[0];
                })->implode(" ");
            }

            $content = trim($request->input('message'));
            if (!$errors && $content) {
                
                (new Message([
                    "user_id" => Auth::id(),
                    "content" => $content,
                ]))->save();

                // Track custom event of posting a message
                // https://segment.com/docs/connections/destinations/catalog/actions-google-analytics-4/#custom-event
                Segment::track([
                    'event' => 'Custom Event',
                    'userId' => $user->id,
                    'eventName' => 'message_posted',
                    'properties' => [
                        'length' => strlen($content)
                    ]
                ]);
            }
        }

        $messages = DB::table('messages')
            ->join('users', 'messages.user_id', '=', 'users.id')
            ->orderBy('messages.created_at', 'desc')
            ->limit(100)
            ->get();

        if ($messages->count() < 1) {
            $messages = [
                (object)[
                    "username" => "unknown",
                    "content" => "A quick brown fox jumps over the lazy dog",
                    "created_at" => Carbon::now()->toString()
                ]
            ];
        }

        return view('bulletin.index', [
            'messages' => $messages,
            'token' => $request->input('token'),
            'username' => $user->username,
            'errors' => $errors
        ]);

    }
}
