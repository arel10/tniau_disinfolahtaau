<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;
use Carbon\Carbon;

class TrackVisitor
{
    public function handle(Request $request, Closure $next)
    {
        // Record one visit per IP per day
        Visitor::firstOrCreate([
            'ip_address' => $request->ip(),
            'visited_at' => Carbon::today()->toDateString(),
        ]);

        return $next($request);
    }
}
