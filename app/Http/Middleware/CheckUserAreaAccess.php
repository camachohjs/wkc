<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TorneoUser;

class CheckUserAreaAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return $next($request);
        }

        $userId = $user->id;
        $torneoId = $request->route('torneoId');
        $areaId = $request->route('areaId');

        $access = TorneoUser::where('user_id', $userId)
            ->where('torneo_id', $torneoId)
            ->where('area', $areaId)
            ->exists();

        if (!$access) {
            flash()->options([
                'position' => 'top-center',
            ])->addError('', 'No tienes acceso a esta área.');
            return redirect()->back();
        }

        return $next($request);
    }
}