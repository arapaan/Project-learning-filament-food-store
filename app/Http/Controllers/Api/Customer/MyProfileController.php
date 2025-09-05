<?php

namespace App\Http\Controllers\Api\Customer;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;

class MyProfileController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth:api'
        ];
    }

    public function index()
    {
        $profile = Customer::query()
            ->where('id', auth()->guard('api')->user()->id)
            ->firstOrFail();

        return response()->json([
            'success'   => true,
            'message'   => 'Detail Profil',
            'Customer'  => $profile
        ]);
    }

    public function update(Request $request)
    {
        $validator = Customer::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email|unique:customers,email'. auth()->guard('api')->user()->id,
            'password'  => 'nullable|min:6|confirmed',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $profile = Customer::query()
            ->where('id', auth()->guard('api')->user()->id)
            ->firstOrFail();

        if ($request->hasFile('image')) {
            if ($profile->image) {
                Storage::delete('avatars/' . $profile->image);
            }

            $image = $request->file('image');
            $image->storeAs('avatars', $image->hashName());

            $profile->image = $image->hashName();
        }        

        $profile->name = $request->name;
        $profile->email = $request->email;

        if ($request->filled('password')) {
            $profile->password = bcrypt($request->password);
        }       

        $profile->save();

        return response()->json([
            'success'   => true,
            'message'   => 'Update Profil Berhasil',         
            'data'      => $profile
        ]);
    }
}
