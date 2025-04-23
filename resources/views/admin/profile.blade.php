@extends('admin.layout')

@section('content')
    <h2>Admin Profile</h2>
    <p>Manage your profile or logout.</p>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="btn btn-danger">Logout</button>
    </form>
@endsection
