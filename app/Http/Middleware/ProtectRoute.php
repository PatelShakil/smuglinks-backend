<?php

namespace App\Http\Middleware;

use App\Models\UserMst;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProtectRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        date_default_timezone_set("Asia/Kolkata");

        if (!str_contains($request->path(), "/common")) {
            if (UserMst::where("uid", $request->header('uid'))->where("active", true)->exists()) {
                return $next($request);
            } else {
                return response()->json([
                    "message" => "Unauthorized access",
                    "status" => false,
                    "data" => null,
                ]);
            }
        } else {
            return $next($request);
        }
    }
}
