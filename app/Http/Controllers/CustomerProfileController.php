<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;

class CustomerProfileController extends Controller
{
    public function show(Request $req)
    {
        $customer = $req->user();

        return response()->json([
            'name' => $customer->name,
            'phone' => $customer->phone,
            'address' => $customer->address,
            'profile_image_url' => $customer->profile_image
                ? asset('storage/' . $customer->profile_image)
                : null,
        ]);
    }

    public function update(Request $req)
    {
        $data = $req->validate([
            
            'name' => 'nullable|string',
            'phone' => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $customer = $req->user();

        if ($req->hasFile('profile_image')) {
            Log::info('✅ profile_image field is present');

            $file = $req->file('profile_image');

            if ($file->isValid()) {
                $path = $file->store('profile_image', 'public');
                \Log::info('✅ File saved at: ' . $path);

                $customer->profile_image = $path;
            } else {
                \Log::error('❌ Uploaded file is not valid.');
            }
        } else {
            \Log::warning('⚠️ No file uploaded in profile_image');
        }

        $customer->name = $data['name'] ?? $customer->name;
        $customer->phone = $data['phone'] ?? $customer->phone;
        
        $customer->save(); // ✅ explicitly save

        // Add full image URL
        $customer->profile_image_url = $customer->profile_image
            ? asset('storage/' . $customer->profile_image)
            : null;

        return response()->json([
            'name' => $customer->name,
            'phone' => $customer->phone,
            'address' => $customer->address,
            'profile_image_url' => $customer->profile_image ? asset('storage/' . $customer->profile_image) : null,
        ]);
    }

    public function logout(Request $req)
    {
        $req->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
