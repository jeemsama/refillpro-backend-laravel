@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-md p-6 bg-white rounded shadow">
  <h2 class="text-2xl font-semibold mb-4">Reset Your Password</h2>

  @if (session('status'))
  <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-800 rounded">
    {{ session('status') }}
  </div>
  <p class="text-center text-gray-700">
    Your password has been reset successfully. Please go back to the app and log in.
  </p>
  @else
    {{-- ERROR MESSAGES --}}
    @if ($errors->any())
      <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-800 rounded">
        <ul class="list-disc list-inside">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- RESET FORM --}}
    <form method="POST" action="{{ route('owner.password.update') }}">
      @csrf
      <input type="hidden" name="token"   value="{{ $token }}">
      <input type="hidden" name="email"   value="{{ $email }}">

      <div class="mb-4">
        <label class="block text-sm font-medium">New Password</label>
        <input type="password" name="password"
               class="mt-1 block w-full border rounded p-2 @error('password') border-red-500 @enderror"
               required autofocus>
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium">Confirm Password</label>
        <input type="password" name="password_confirmation"
               class="mt-1 block w-full border rounded p-2" required>
      </div>

      <button type="submit"
              class="w-full bg-blue-600 hover:bg-blue-700 text-black font-semibold py-2 rounded">
        Reset Password
      </button>
    </form>
  @endif
</div>
@endsection
