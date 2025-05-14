<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rider;      // ← correct model namespace
use Illuminate\Support\Facades\Hash;


class RiderController extends Controller
{
    public function index(Request $request)
    {
        // \Log::info('BearerToken: '.$request->bearerToken());
        $owner = $request->user();
        $riders = Rider::where('owner_id', $owner->id)->get(['id','name','phone']);
        // $bearer = $request->bearerToken();  
        // $all    = $request->headers->all();

        return response()->json(
            $riders,
            // 'you_sent_bearer' => $bearer,
            // 'all_headers'     => $all,
        200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:riders,email',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
        ]);

        $rider = Rider::create([
            'owner_id' => $request->user()->id,
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Rider created', 'rider' => $rider]);
    }

    public function update(Request $request, $id)
    {
        $rider = Rider::where('owner_id', $request->user()->id)->findOrFail($id);

        $rider->update($request->only(['name', 'email', 'phone']));

        return response()->json(['message' => 'Rider updated', 'rider' => $rider]);
    }

    public function destroy(Request $request, $id)
    {
        $rider = Rider::where('owner_id', $request->user()->id)->findOrFail($id);

        $rider->delete();

        return response()->json(['message' => 'Rider deleted']);
    }
}
