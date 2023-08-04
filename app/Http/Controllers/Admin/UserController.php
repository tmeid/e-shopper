<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //
    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index(Request $request){
        $usersResult = $this->userRepo->userPaginate($request);
        $users = $usersResult['users'];
        $search = $usersResult['search'];
        return view('admin.user.index')->with(['users' => $users, 'search' => $search]);
    }

    public function delete(User $user){
        $id = $user->id;
        if($this->userRepo->find($id)){
            $deletedNum = $this->userRepo->delete($user->id);
            if($deletedNum){
                $msg = 'Xoá mềm thành công';
                $type = 'success';
            }else{
                $msg = 'Đã có lỗi xảy ra';
                $type = 'danger';
            }
        }else{
            abort('401');
        }
        return redirect()->route('admin.user.list')->with(['msg' => $msg, 'type' => $type]);
       
       
    }

    public function restore($id){
        $trashedUser = $this->userRepo->getTrashedUser($id);
        if($trashedUser){
            $trashedUser->restore();
            $msg = 'Khôi phục thành công';
            $type = 'success';
        }else{
            $msg = 'Người dùng không tồn tại';
            $type = 'danger';
        }
        return redirect()->route('admin.user.list')->with(['msg' => $msg, 'type' => $type]);
    }

    public function forceDelete(Request $request){
        $id = $request->id;
        $trashedUser = $this->userRepo->getTrashedUser($id);
        if($trashedUser){
            $trashedUser->forceDelete();
            $msg = 'Xoá thành công';
            $type = 'success';
        }else{
            $msg = 'Người dùng không tồn tại';
            $type = 'danger';
        }
        return redirect()->route('admin.user.list')->with(['msg' => $msg, 'type' => $type]);
    }

    public function edit(User $user){
        return view('admin.user.edit')->with('user', $user);
    }
    public function postEdit(Request $request, User $user){
        $request->validate([
            'name' => ['required', 'string', 'max:30', function($attribute, $value, $fail){ 
                $reg = '/(?=.*[0-9])/';
                if(preg_match($reg, $value)){
                    $fail('Tên không được chứa số');
                }

            }],
            'email' => 'required|string|email|max:255|unique:users,email,' .$user->id,
            'phone' => ['required', 'string', function($attribute, $value, $fail){
                // 
                $reg = '/(\+84|0)[35789]([0-9]{8})$/';
                if(!preg_match($reg, $value)){
                    $fail('Định dạng số điện thoại không hợp lệ');
                }
            }],
            'role' => ['required', function($attribute, $value, $fail){
                $allow_roles = ['0', '1'];
                if(!in_array($value, $allow_roles)){
                    $fail('Giá trị không hợp lệ');
                }
            }]
        ], [
            'name.required' => 'Trường tên bị trống',
            'string' => 'Định dạng không hợp lệ',
            'name.max' => 'Tên quá dài',
            'email.required' => 'Trường email bị trống',
            'email.email' => 'Định dạng email không hợp lệ',
            'email.unique' => 'Email đã tồn tại trong hệ thống',
            'phone' => 'Trường điện thoại bị trống',
            'role.required' => 'Bị trống giá trị'
        ]);

        $data = [];
        if($request->name != $user->name){
            $data['name'] = trim($request->name);
        }

        if($request->phone != $user->phone){
            $data['phone'] = trim($request->phone);
        }
        if($request->email != $user->email){
            $data['email'] = trim($request->email);
        }
        if($request['role'] != $user->role){
            $data['role'] = $request->role;
        }

        if(!empty($data)){
            $upadeStatus = $this->userRepo->edit($data, $user->id);
            if($upadeStatus){
                $msg = 'Sửa thành công';
                $type = 'success';
            }
        }else{
            $msg = 'Bạn chưa thay đổi dữ liệu gì';
            $type = 'danger';
        }
        return redirect()->route('admin.user.list')->with(['msg' => $msg, 'type' => $type]);
        
        
    }
}
