<?php

namespace App\Http\Middleware;

use App\Models\VisitorLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            VisitorLog::logVisit(
                $request->ip(),
                $request->path(),
                $request->userAgent()
            );
        } catch (\Throwable $e) {
            // Silently fail — visitor tracking should never break the site
        }

        // Share visitor stats with all views
        try {
            $visitorStats = VisitorLog::getStats();
        } catch (\Throwable $e) {
            $visitorStats = ['total' => 0, 'today' => 0, 'this_month' => 0, 'this_year' => 0];
        }

        view()->share('visitorStats', $visitorStats);

        return $next($request);
    }
}
