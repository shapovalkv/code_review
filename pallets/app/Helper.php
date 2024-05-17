<?php

namespace App;

use App\Models\Lead;
use Illuminate\Support\Facades\Session;

class Helper
{
    public static function check_lead_session($returnLead = false)
    {
        if (Session::exists('lead_id')) {
            return true;
        }
    }

    public static function get_lead_from_session()
    {
        if (!empty(Session::get('lead_id'))){
            $lead = Lead::find(Session::get('lead_id'));
            return $lead ?? Session::flush();
        }
    }

    public static function clearSession() : void
    {
       Session::flush();
    }
}
