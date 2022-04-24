<?php

namespace App\Http\Middleware;

use App\Helpers\DbTablesHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class logConnexion
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //if (!$request->ajax()){
          //  $this->logConnexion ();
        //}
        return $next($request);
    }

    public function logConnexion(){
        DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_LOG_CONNEXIONS'))
            ->where('timelife','<', time ())
            ->update ([
                'actif' => 0
            ]);
        $re = DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_LOG_CONNEXIONS'))
            ->where([
                'session_id' => Session::getId (),
            ])->get ();
        if ($re[0]->actif === 0):
            Session::flush ();
            return redirect ('/login');
        else:
            $timeLife = time () + intval (env ("SESSION_LIFETIME"))*60;
            DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_LOG_CONNEXIONS'))
                ->where('session_id','=', Session::getId ())
                ->update ([
                    'timelife' => $timeLife
                ]);
        endif;
    }


}
