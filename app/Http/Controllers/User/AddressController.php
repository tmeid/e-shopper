<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\Address\AddressRepository;
use App\Rules\PhoneValid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    //
    protected $addressRepo;
    public function __construct(AddressRepository $addressRepo)
    {
        $this->addressRepo = $addressRepo;
    }
    public function address(){
        $addresses = $this->addressRepo->getAddress();
        return view('user.address.index')->with('addresses', $addresses);
    }
    public function add(Request $request){
        if($request->ajax()){
            $validator = Validator::make($request->all(), [
                'addName' => 'required',
                'addPhone' => ['required', new PhoneValid],
                'addAddress' => 'required'
            ], [
                'required' => 'Bị trống'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }else{
                $name = trim($request->addName);
                $phone = trim($request->addPhone);
                $address = trim($request->addAddress);

                $addressObj = $this->addressRepo->create([
                    'name' => $name,
                    'phone' => $phone,
                    'address' => $address,
                    'user_id' => Auth::user()->id
                ]);
                if($addressObj){
                    $data['id'] = $addressObj->id;
                    return response()->json(['status' => 200, 'data' => ['name' => $name, 'phone' => $phone, 'address' => $address]]);
                }else{
                    return response()->json(['status' => 304]);
                }
            }
        }
    }
    public function edit(Request $request){
        if($request->ajax()){
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => ['required', new PhoneValid],
                'address' => 'required'
            ], [
                'required' => 'Bị trống'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }else{
                $address_id = $request->id;
                $address = $this->addressRepo->find($address_id);
                
                if($address){
                    $data = [
                        'name' => trim($request->name),
                        'address' => trim($request->address),
                        'phone' => trim($request->phone)
                    ];
                    $updateStatus = $this->addressRepo->edit($data, $address_id);
                    if($updateStatus){
                        return response()->json(['status' => 200, 'data' => $data]);
                    }else{
                        return response()->json(['status' => 304]);
                    }
                }else{
                    return response()->json(['status' => 404]);
                }
            }
        }
    }

    public function delete($id, Request $request){
        if($request->ajax()){
            $address = $this->addressRepo->find($id);
            if($address){
                $numDelete = $this->addressRepo->delete($id);
                if($numDelete){
                    return response()->json(['status' => 200]);
                }else{
                    return response()->json(['staus' => 304]);
                }
            }else{
                return response()->json(['status' => 404]);
            }
        }
    }
}
