<?php

namespace App\Http\Controllers\Api\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //set Validation 
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required|email|unique::customers',
            'password' => 'required|min:8|confirmed' 
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //Create customer
        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
    }
}
