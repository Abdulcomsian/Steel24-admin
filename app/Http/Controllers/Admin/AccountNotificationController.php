<?php

namespace App\Http\Controllers\Admin;

use App\Http\AppConst;
use App\Http\Controllers\Controller;
use App\Models\{accountNotification , AdminNotification};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountNotificationController extends Controller
{
    public function requestativateaccount(Request $request, $customername, $lotId, $customerId)
    {
        // $requestData = $request->validate([
        //     'userId' => 'required',
        //     'reviewStatus' => 'required',
        // ]);
        AdminNotification::create([
            'customername' => $customername,
            'lotId'  => $lotId,
            'customerId' => $customerId,
            // 'reviewStatus' => 0,
        ]);
        return json_encode(["message" => 'account ativate request sent.', 'sucess' => true]);
    }

    public function index(Request $request)
    {
        // $notifications = DB::select("SELECT customers.*,admin_notifications.customername,admin_notifications.lotId,admin_notifications.customerId FROM `admin_notifications` 
        // LEFT JOIN customers on customers.id = admin_notifications.customername    
        // -- WHERE account_notifications.reviewStatus = 0 
        // GROUP BY admin_notifications.customername ORDER BY admin_notifications.id asc;");

        $notifications = AdminNotification::where(function($query){
                            $query->where('notification_status' , AppConst::NOTIFICATION_PENDING)->orWhereNull('notification_status');
                        })->get();
        
        return view('admin.usernotification', compact('notifications'));
    }


    public function rejectNotification(Request $request){
        $id = $request->notificationId;

       $notification = AdminNotification::find($id);
       $notification->notification_status = AppConst::NOTIFICATION_REJECTED;
       $notification->save();

       return redirect()->back()->with(['success' => true , 'notification_status' => 'Notification Rejected Successfully']);

    }

}
