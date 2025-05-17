<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerProfileController extends Controller
{
    public function show(Request $req)
    {
        return response()->json($req->user());
    }

    public function update(Request $req)
    {
        $data = $req->validate([
            'name'=>'nullable|string',
            'phone'=>'nullable|string',
            'address'=>'nullable|string',
        ]);
        $req->user()->update($data);
        return response()->json($req->user());
    }

    public function logout(Request $req)
    {
        $req->user()->currentAccessToken()->delete();
        return response()->json(['message'=>'Logged out']);
    }
}
