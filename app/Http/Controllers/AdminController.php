<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\RefillingStationOwner;


class AdminController extends Controller
{
    public function dashboard()
    {
        $totalOwners = RefillingStationOwner::count();
        $pendingOwners = RefillingStationOwner::where('status', 'pending')->count();

        return view('admin.dashboard', compact('totalOwners', 'pendingOwners'));
    }

    public function showRequests()
    {
        $pendingOwners = RefillingStationOwner::where('status', 'pending')->get();
        return view('admin.requests', compact('pendingOwners'));
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function approve($id)
    {
        $owner = RefillingStationOwner::findOrFail($id);
        $owner->status = 'approved';
        $owner->save();
    
        // Send email to owner
        Mail::to($owner->email)->send(new \App\Mail\StationApprovedMail($owner));
    
        // return redirect()->route('admin.requests')->with('success', 'Owner approved and notified by email.');
        return redirect()->route('admin.approved_shops')->with('success', 'Owner approved and moved to Approved Shops.');
    }

    public function declineOwner(Request $request)
    {
        $request->validate([
            'owner_id' => 'required|exists:refilling_station_owners,id',
            'decline_reason' => 'required|string',
        ]);
    
        $owner = RefillingStationOwner::findOrFail($request->owner_id);
    
        // Store data temporarily for email before delete
        $ownerEmail = $owner->email;
        $declineReason = $request->decline_reason;
    
        // Send email before delete
        Mail::to($ownerEmail)->send(new \App\Mail\DeclineOwnerMail((object)[
            'name' => $owner->name,
            'email' => $ownerEmail,
            'reason' => $declineReason,
        ]));
    
        // Then delete from database
        $owner->delete();
    
        return redirect()->back()->with('status', 'Owner declined and data deleted successfully.');
    }

    
    public function pauseOwner($id)
    {
        $owner = RefillingStationOwner::findOrFail($id);
        $owner->is_visible = false;
        $owner->save();

        return back()->with('success', 'Station has been paused (hidden from customers).');
    }

    public function continueOwner($id)
    {
        $owner = RefillingStationOwner::findOrFail($id);
        $owner->is_visible = true;
        $owner->save();

        return back()->with('success', 'Station is now visible to customers again.');
    }
    
    public function showApprovedOwners()
    {
        $approvedOwners = RefillingStationOwner::where('status', 'approved')->get();
        return view('admin.approved_shops', compact('approvedOwners'));

    }

}
