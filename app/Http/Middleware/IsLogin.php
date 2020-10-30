<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class IsLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!!Session::get('user_name')){
            return $next($request);
        }else{
            return response()->json(array("code"=> 401, "msg" => "请先登录！"));
        }
    }
}
