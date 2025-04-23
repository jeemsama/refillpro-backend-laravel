<!-- resources/views/admin/dashboard.blade.php -->
@extends('admin.layout')

@section('content')
    <h2>Admin Dashboard</h2>
    <p>Welcome back, admin!</p>

    <div class="row">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Total Shop Owners</h5>
                    <h2>{{ $totalOwners }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 mt-3 mt-md-0">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h5>Pending Requests</h5>
                    <h2>{{ $pendingOwners }}</h2>
                </div>
            </div>
        </div>
    </div>
@endsection
