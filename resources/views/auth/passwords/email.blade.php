@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-md p-6 bg-white rounded shadow">
  <h2 class="text-2xl font-semibold mb-4">Owner Password Reset</h2>

  @if (session('status'))
    <div class="mb-4 text-green-600">
      {{ session('status') }}
    </div>
  @endif

  <form method="POST" action="{{ url($url.'/password/email') }}">
    @csrf

    <div class="mb-4">
      <label for="email" class="block text-sm font-medium">Email Address</label>
      <input id="email" type="email" name="email" value="{{ old('email') }}"
             class="mt-1 block w-full border rounded p-2 @error('email') border-red-500 @enderror"
             required autofocus>
      @error('email')
        <span class="text-red-600 text-sm">{{ $message }}</span>
      @enderror
    </div>

    <button type="submit"
            class="w-full bg-blue-600 text-black py-2 rounded hover:bg-blue-700">
      Send Password Reset Link
    </button>
  </form>
</div>
@endsection
