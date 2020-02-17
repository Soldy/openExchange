<?php

namespace App\Extras;

use App\ExLog;


class ExLogExtra
{
    public function add ($to="",$form="",$amount=""){
        if($to=="")
            return false;
        if($form=="")
            return false;
        if($amount=="")
            return false;
        $log = new ExLog();
        $log->to=$to;
        $log->form=$form;
        $log->amount=$amount;
        $log->save();
        return $log->id;
    }
}
