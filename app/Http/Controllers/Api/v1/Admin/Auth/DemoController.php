<?php
namespace App\Http\Controllers\Api\v1\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DemoController extends Controller
{
    public function open()
    {
     $result = array(
         ['name'=>'Test1','email'=>'test1@example.com','number'=>'1231'],
         ['name'=>'Test2','email'=>'test2@example.com','number'=>'1232'],
         ['name'=>'Test3','email'=>'test3@example.com','number'=>'1233'],
    );
    // $page = (object)$page;
    // dd($page[0]['title']);

        // $name = ['Hensi', 'Miral','Visu'];
        // $email = ['hensi@gmail.com','miral@gmail.com','visu@gmail.com'];
        // $number = [24000, 34000, 44000];
        // $data = [];
        // for ($i = 0; $i < count($name); $i++) {
        //     $data[] = [
        //         'name' =>  $name[$i],
        //         'email' => $email[$i],
        //         'password' =>  $number[$i],
        //     ];
        // }
        return response()->json(compact('result'), 200);
    }

    public function closed()
    {
        $data = "Only authorized users can see this";
        return response()->json(compact('data'), 200);
    }
}
