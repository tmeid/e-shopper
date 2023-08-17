<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:30', function($attribute, $value, $fail){ 
                $reg = '/(?=.*[0-9])/';
                if(preg_match($reg, $value)){
                    $fail('Tên không được chứa số');
                }

            }],
            'email' => ['required', 'email', 'max:255', 'unique:users', function($attribute, $value, $fail){
                    // remove a dot character before @ character because both of them are same email: 
                    // d.thuy.3319@gmai.com ==> dthuy3319@gmail.com
                    $pattern = '/\.(?=[^\s]*@)/';
                    $value = preg_replace($pattern, '', $value);
                    $findEmail = User::where('email', $value)->first();
                    if($findEmail){
                        $fail('Email đã tồn tại');
                    }
            }],
            'phone' => ['required', 'string', function($attribute, $value, $fail){
                // 
                $reg = '/(\+84|0)[35789]([0-9]{8})$/';
                if(!preg_match($reg, $value)){
                    $fail('Định dạng số điện thoại không hợp lệ');
                }
            }],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ], [
            'name.required' => 'Trường tên bị trống',
            'string' => 'Định dạng không hợp lệ',
            'name.max' => 'Tên quá dài',
            'email.required' => 'Trường email bị trống',
            'email.email' => 'Định dạng email không hợp lệ',
            'email.unique' => 'Email đã tồn tại trong hệ thống',
            'phone.required' => 'Trường điện thoại bị trống',
            'password.required' => 'Trường mật khẩu bị trống',
            'password.min' => 'Trường mật khẩu phải có ít nhất 8 kí tự',
            'password.confirmed' => 'Mật khẩu không khớp nhau'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function showRegistrationForm()
    {
        if (!session()->has('url.intended')) {
            session(['url.intended' => url()->previous()]);
        }
        return view('auth.register');
    }

    
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
                    ? new JsonResponse([], 201)
                    : redirect()->intended($this->redirectPath());
    }

    
}
