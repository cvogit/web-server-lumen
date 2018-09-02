<?php

namespace App\Http\Middleware;

use App\User;
use App\Helpers\RequestHelper;
use Closure;
use Illuminate\Http\Request;

class UserMiddleware extends Middleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle(Request $request, Closure $next)
	{
		$request['req']= $this->req;

		return $next($request);
	}
}