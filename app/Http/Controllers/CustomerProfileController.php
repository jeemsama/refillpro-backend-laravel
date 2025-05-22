<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;

class CustomerProfileController extends Controller
{
    public function show(Request $req)
    {
        $user = $req->user();

        return response()->json([
            'name' => $user->name,
            'phone' => $user->phone,
            'address' => $user->address,
            'profile_image_url' => $user->profile_image ? asset('storage/' . $user->profile_image) : null,
        ]);
    }

    public function update(Request $req)
    {
        $data = $req->validate([
            
            'name' => 'nullable|string',
            'phone' => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $user = $req->user();

        if ($req->hasFile('profile_image')) {
            Log::info('✅ profile_image field is present');

            $file = $req->file('profile_image');

            if ($file->isValid()) {
                $path = $file->store('profile_images', 'public');
                \Log::info('✅ File saved at: ' . $path);

                $user->profile_image = $path;
            } else {
                \Log::error('❌ Uploaded file is not valid.');
            }
        } else {
            \Log::warning('⚠️ No file uploaded in profile_image');
        }

        $user->name = $data['name'] ?? $user->name;
        $user->phone = $data['phone'] ?? $user->phone;
        
        $user->save(); // ✅ explicitly save

        // Add full image URL
        $user->profile_image_url = $user->profile_image
            ? asset('storage/' . $user->profile_image)
            : null;

        return response()->json([
            'name' => $user->name,
            'phone' => $user->phone,
            'address' => $user->address,
            'profile_image_url' => $user->profile_image ? asset('storage/' . $user->profile_image) : null,
        ]);
    }

    public function logout(Request $req)
    {
        $req->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
