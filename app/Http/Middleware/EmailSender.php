<?php

namespace App\Http\Middleware;

use App\Models\Email;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmailSender
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // проверяем валидность данных
        $valid = Validator::make($request->all(), [
            'message_' => 'required|max:' . MAX_MESSAGE_LENGHT,
            'content' => 'required|max:' . MAX_CONTENT_LENGHT,
        ]);

        if ($valid->fails()) {
            return response(['errors' => $valid->errors()], 422);
        }

        // проверяем количество писем на пользователя
        $ip = $request->ip();

        $count = Email::where('sender', $ip)
            ->count();

        return $count >= MAX_PER_SENDER
            ? response(['error' => 'Limit reached'], 401)
            : $next($request);
    }
}
