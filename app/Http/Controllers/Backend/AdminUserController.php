<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\AdminUser;

use Jenssegers\Agent\Agent;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreAdminUser;

use App\Http\Requests\UpdateAdminUser;
use Yajra\DataTables\Facades\DataTables;

class AdminUserController extends Controller
{
    public function index(){
        // $users=AdminUser::all();
        // return view('backend.admin_user.index',compact('users'));
        return view('backend.admin_user.index');
    }

    // server side data
    public function ssd(){
        $data=AdminUser::query();

        return DataTables::of($data)
        ->editColumn('user_agent',function($each){
            if ($each->user_agent) {
                $agent = new Agent();
                $agent->setUserAgent($each->user_agent);
                $device = $agent->device();
                $platform = $agent->platform();
                $browser = $agent->browser();

                return '<table class="table table-bordered">
                    <tr><td>Device</td><td>'.$device.'</td></tr>
                    <tr><td>Platform</td><td>'.$platform.'</td></tr>
                    <tr><td>Browser</td><td>'.$browser.'</td></tr>
                </table>';
            }else{
                return '-';
            }

        })

        ->editColumn('created_at',function($each){
            return Carbon::parse($each->created_at)->format('Y-m-d H:i:s');
        })

        ->editColumn('updated_at',function($each){
            return Carbon::parse($each->updated_at)->format('Y-m-d H:i:s');
        })

        ->addColumn('action',function($each){
            $edit_icon = '<a href="'.route('admin.admin-user.edit',$each->id).'" class="text-warning"><i class="fas fa-edit"></i></a>';
            $delete_icon = '<a href="" class="text-danger delete" data-id="'.$each->id.'"><i class="fas fa-trash-alt"></i></a>';
            return '<div class="action-icon">'.$edit_icon . $delete_icon.'</div>' ;
        })
        ->rawColumns(['user_agent','action'])
        ->make(true);
    }

    public function create(){
        return view('backend.admin_user.create');
    }

    public function store(StoreAdminUser $request){
        $admin_user = new AdminUser();
        $admin_user->name = $request->name;
        $admin_user->email = $request->email;
        $admin_user->phone = $request->phone;
        $admin_user->password = Hash::make($request->password);

        $admin_user->save();

        return redirect()->route('admin.admin-user.index')->with('create','Successfully Created');
                                                          //with('session'),('session message);

        // return $request->all();
    }

    public function edit($id){
        $admin_user = AdminUser::findOrFail($id);
        return view('backend.admin_user.edit',compact('admin_user'));
    }

    public function update($id,UpdateAdminUser $request){
        $admin_user = AdminUser::findOrFail($id);
        $admin_user->name = $request->name;
        $admin_user->email = $request->email;
        $admin_user->phone = $request->phone;
        $admin_user->password = $request->password ? Hash::make($request->password) : $admin_user->password;

        $admin_user->update();

        return redirect()->route('admin.admin-user.index')->with('update','Successfully Updated');
                                                          //with('session'),('session message);

        // return $request->all();
    }

    public function destroy($id){
        $admin_user = AdminUser::findOrFail($id);

        $admin_user->delete();

        return 'success';
    }
}
