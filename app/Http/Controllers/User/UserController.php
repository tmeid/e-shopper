<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\Order\OrderRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    protected $orderRepo;
    protected $userRepo;

    public function __construct(OrderRepository $orderRepo, UserRepository $userRepo)
    {
       $this->orderRepo = $orderRepo; 
       $this->userRepo = $userRepo;
    }
    public function index(){
        $user_id = Auth::user()->id;
        $myOrder = $this->orderRepo->getAllOrders(['user_id' => $user_id]);
        return view('user.index')->with('myOrder', $myOrder);
    }

    public function show(){
        $user_id = Auth::user()->id;
        $myOrder = $this->orderRepo->getAllOrders(['user_id' => $user_id]);
        return view('user.order.index')->with('myOrder', $myOrder);
    }

    public function detailOrder($id){
        $order = $this->orderRepo->find($id);
        return view('user.order.show')->with('order', $order);
    }

    public function showFormchangePass(){
        return view('user.password.index');
    }

    public function changePass(Request $request){
        $request->validate([
            'old-pass' => ['required', function($attribute, $value, $fail){
                if(!password_verify($value, Auth::user()->password)){
                    $fail('Mật khẩu cũ không khớp');
                }
            }],
            'newPass' => 'required|string|min:8',
            'reNewPass' => 'required|string|min:8|same:newPass'
        ], [
            'required' => 'Chưa nhập dữ liệu',
            'min' => 'Trường mật khẩu phải có ít nhất :min kí tự',
            'same' => 'Mật khẩu không khớp nhau'
        ]);

        $updateStatus = $this->userRepo->edit(['password' => Hash::make($request->newPass)], Auth::user()->id);
        if($updateStatus){
            $msg = 'Đổi mật khẩu thành công, vui lòng đăng nhập lại';
            $type = 'success';
            Auth::logout();
            return redirect()->route('login')->with(['msg' => $msg, 'type' => $type]); 
        }else{
            $msg = 'Đã có lỗi xảy ra';
            $type = 'danger';
            return redirect()->route('user.showFormchangePass')->with(['msg' => $msg, 'type' => $type]);
        }
    }
}
