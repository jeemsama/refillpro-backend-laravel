@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-md p-6 bg-white rounded shadow">
  <h2 class="text-2xl font-semibold mb-4">Reset Your Password</h2>

  {{-- Show success and bail out --}}
  @if (session('status'))
    <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-800 rounded">
      {{ session('status') }}
    </div>

    <a href="{{ route('owner.login') }}"
       class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded">
      Go to Login
    </a>

    {{-- Prevent the form from rendering --}}
    @return
  @endif

  {{-- Show validation errors --}}
  @if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-800 rounded">
      <ul class="list-disc list-inside">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Only render the form if we have no status message --}}
<form method="POST" action="{{ route('owner.password.update') }}">
    @csrf

    <input type="hidden" name="token"   value="{{ $token }}">
    <input type="hidden" name="email"   value="{{ $email }}">

    <div class="mb-4">
      <label for="password" class="block text-sm font-medium">New Password</label>
      <input id="password" type="password" name="password"
             class="mt-1 block w-full border rounded p-2 @error('password') border-red-500 @enderror"
             required autofocus>
    </div>

    <div class="mb-4">
      <label for="password_confirmation" class="block text-sm font-medium">Confirm Password</label>
      <input id="password_confirmation" type="password" name="password_confirmation"
             class="mt-1 block w-full border rounded p-2" required>
    </div>

    <button type="submit"
            class="w-full bg-blue-600 text-black py-2 rounded hover:bg-blue-700">
      Reset Password
    </button>
  </form>
</div>
@endsection
