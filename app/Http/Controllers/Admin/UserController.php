<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\{ Mail , Validator};


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::all();
        if ($request->ajax()) {
          return Datatables::of($users)
                  ->addIndexColumn()
                  ->rawColumns(['action'])
                  ->make(true);
      }
        return view('admin.user.index')
            ->with('users', $users);

        // return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        User::create($request->all());
        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('admin.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user->update($request->all());

        return redirect()->route('admin.users.index');
    }
    public function status(Request $request, $id, $type)
    {
        $status = User::find($id);
        $status->isApproved = $type;
        $status->save();
        $request->session()->flash("massege", "status updated successfuly");
        return back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect('/admin/users');
    }

    public function contactUs(Request $request){
        $validator = Validator::make($request->all(),[
            'username' => 'required|string',
            'email' => 'required|email',
            'message' => 'required|string'
        ]);
        
        if($validator->fails()){
            return response()->json(['status' => false , 'msg' => $validator->getMessageBag()]);
        }
        
        try{

            $username = $request->username;
            $email = $request->email;
            $msg = $request->message;

            Mail::to('sales.steel24@gmail.com')->send(new \App\Mail\ContactMail($username , $email , $msg));

            return response()->json(['status' => true , 'msg' => 'Mail Send Successfully']);

        }catch(\Exception $e){
            return response()->json(['status' => false , 'msg' => $e->getMessage()]);
        }
    }
}
