<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class checkUserExistence
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $mobileNumber = $request->input('mobile_number');

        if (User::where('mobile_number', $mobileNumber)->exists()) {
            return response()->json(['status' => 'false','status_code'=> 422,'msg' => 'Mobile number already exists']); // You can return an appropriate response for a non-unique number
        }
        return $next($request);
    }
}
