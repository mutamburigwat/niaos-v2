<?php

namespace App\Http\Middleware;

use App\Services\WorkspaceContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveWorkspaceIsValid
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->active_workspace_id !== null) {
                $belongs = WorkspaceContext::userBelongsToWorkspace($user->active_workspace_id);

                if (! $belongs) {
                    $user->active_workspace_id = null;
                    $user->save();
                }
            }
        }

        return $next($request);
    }
}
