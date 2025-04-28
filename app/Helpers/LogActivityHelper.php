<?php
namespace App\Helpers;

use App\Models\LogActivity as ModelsLogActivity;
use App\Models\Notifikasi;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Facades\Agent;

use Browser;

class LogActivityHelper
{
    
    public static function push_log($message)
    {
        $ip = Request()->ip();
        $browser = Agent::browser().' '.Agent::version(Agent::browser());
        $device = Agent::device();

        if (isset(Auth::user()->id)) {
            $user_id = Auth::user()->id;
        } else {
            $user_id = NULL;
        }
        
        $data = [
            'message' => $message,
            'ip' => $ip,
            'browser' => $browser,
            'device' => $device,
            'created_by' => $user_id,
        ];

        $log = ModelsLogActivity::create($data);

        return $log->id;
    }

    public static function pull_log()
    {        
        return Notifikasi::where('receiver_id', Auth::user()->id)->orderBy('notifikasi.id','DESC');
    }
}