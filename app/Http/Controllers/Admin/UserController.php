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
}
