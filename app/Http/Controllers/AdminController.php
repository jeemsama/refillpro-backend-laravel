<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\RefillingStationOwner;
use App\Models\Rider;
use App\Models\RefillingStation;  // <-- if you want to actually create the shop record

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalOwners   = RefillingStationOwner::count();
        $pendingOwners = RefillingStationOwner::where('status', 'pending')->count();
        $totalRiders   = Rider::count();

        return view('admin.dashboard', compact('totalOwners', 'pendingOwners', 'totalRiders'));
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

    /**
     * (Optional) GET-driven preview of approval.
     * You can remove this if you only ever approve via modal+POST.
     */
    public function approve($id)
    {
        $owner = RefillingStationOwner::findOrFail($id);
        return view('admin.approve_preview', compact('owner'));
    }

    /**
     * Handles the POST from your “Yes, Approve” modal.
     */
    public function approveOwner(Request $request)
    {
        $request->validate([
            'owner_id' => 'required|exists:refilling_station_owners,id',
        ]);

        $owner = RefillingStationOwner::findOrFail($request->owner_id);

        // mark approved
        $owner->status = 'approved';
        // $owner->approved_at = now();
        $owner->save();

        // (Optional) actually create the shop record
        // RefillingStation::create([
        //     'owner_id'  => $owner->id,
        //     'shop_name' => $owner->shop_name,
        //     'address'   => $owner->address,
        //     // …any other defaults…
        // ]);

        // notify owner
        Mail::to($owner->email)
            ->send(new \App\Mail\StationApprovedMail($owner));

        return redirect()
               ->route('admin.approved_shops')
               ->with('success', 'Owner approved and shop created.');
    }

    public function declineOwner(Request $request)
    {
        $request->validate([
            'owner_id'       => 'required|exists:refilling_station_owners,id',
            'decline_reason' => 'required|string',
        ]);

        $owner        = RefillingStationOwner::findOrFail($request->owner_id);
        $ownerEmail   = $owner->email;
        $declineReason= $request->decline_reason;

        Mail::to($ownerEmail)
            ->send(new \App\Mail\DeclineOwnerMail((object)[
                'name'   => $owner->name,
                'email'  => $ownerEmail,
                'reason' => $declineReason,
            ]));

        $owner->delete();

        return redirect()
               ->back()
               ->with('status', 'Owner declined and data deleted successfully.');
    }

    public function pauseOwner($id)
    {
        $owner = RefillingStationOwner::findOrFail($id);
        $owner->is_visible = false;
        $owner->save();

        return back()->with('success', 'Station has been paused.');
    }

    public function continueOwner($id)
    {
        $owner = RefillingStationOwner::findOrFail($id);
        $owner->is_visible = true;
        $owner->save();

        return back()->with('success', 'Station is now visible.');
    }

    public function showApprovedOwners()
    {
        $approvedOwners = RefillingStationOwner::where('status', 'approved')->get();
        return view('admin.approved_shops', compact('approvedOwners'));
    }
}
