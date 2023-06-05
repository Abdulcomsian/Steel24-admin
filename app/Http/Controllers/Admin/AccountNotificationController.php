<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;


use App\Models\accountNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountNotificationController extends Controller
{
    public function requestativateaccount(Request $request, $userId)
    {
        // $requestData = $request->validate([
        //     'userId' => 'required',
        //     'reviewStatus' => 'required',
        // ]);
        accountNotification::create([
            'userId' => $userId,
            'reviewStatus' => 0,
        ]);
        return json_encode(["message" => 'account ativate request sent.', 'sucess' => true]);
    }

    public function index(Request $request)
    {
        $notifications = DB::select("SELECT customers.* FROM `account_notifications` 
        LEFT JOIN customers on customers.id = account_notifications.userId   
        WHERE account_notifications.reviewStatus = 0 
        GROUP BY account_notifications.userId ORDER BY account_notifications.id DESC;");
        
        return view('admin.usernotification', compact('notifications'));
    }
}
