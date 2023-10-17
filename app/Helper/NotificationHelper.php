<?php

namespace App\Helper;

use App\Http\AppConst;
use App\Models\AdminNotification;

class NotificationHelper{

    public static function countNotification(){
      return  AdminNotification::where(function($query){
                                $query->where('notification_status' , AppConst::NOTIFICATION_PENDING)->orWhereNull('notification_status');
                            })->count();
    }

}