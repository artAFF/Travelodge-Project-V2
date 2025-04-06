<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CheckAdminOrDepartment
{
    public function handle(Request $request, Closure $next, ...$departmentIds)
    {
        if (!$request->user()) {
            return response()->view('errors.403', [
                'exception' => new \Exception('Unauthorized action')
            ], 403);
        }

        if ($request->user()->role === 'admin') {
            return $next($request);
        }

        if (!in_array($request->user()->department_id, $departmentIds)) {
            return response()->view('errors.403', [
                'exception' => new \Exception('Unauthorized action')
            ], 403);
        }

        return $next($request);
    }
}
