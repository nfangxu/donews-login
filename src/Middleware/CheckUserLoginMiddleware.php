<?php
/**
 * Created by PhpStorm.
 * User: nfangxu
 * Date: 2018/9/21
 * Time: 16:48
 */

namespace Fangxu\Middleware;

use Fangxu\Exception\UserLoginException;
use Illuminate\Support\Facades\Redis;
use Fangxu\Tools;

class CheckUserLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $token = $request->header("token");

        $user = Tools::decode($token);

        if (!$user) {
            throw new UserLoginException("Token is not valid ", 401);
        }

        $redis = Redis::get($request->app_id . ":login:" . $user->id);

        if (!$redis) {
            throw new UserLoginException("you need to login in first", 404);
        }

        if ($token != $redis) {
            throw new UserLoginException("This account is already logged in elsewhere", 410);
        }

        $request->user = $user;

        return $next($request);
    }

}
