<?php

namespace App\Http\Controllers;

use App\Models\Email;
use Illuminate\Http\Request;


class EmailController extends Controller
{

    public function store(Request $request)
    {
        try {

            Email::insert([
                'sender' => $request->ip(),
                'message' => $request->get('message_'),
                'content' => $request->get('content'),
            ]);

            return response(null, 200);

        } catch (\Exception $e) {

            return response(['error' => 'PDOException'], 422);

        }
    }

}
