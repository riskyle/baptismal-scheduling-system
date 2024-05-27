@extends('layouts.auth')
@section('content')
    <!-- Session Status -->
    @if (session('status'))
        <div>
            {{ $status }}
        </div>
    @endif
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <h3 class="text-center mb-5 text-light">Login!</h3>

        <!-- Email Address -->
        <div>
            {{-- <label for="email">{{ __('Email') }}</label> --}}
            <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}"
                placeholder="{{ __('Email') }}" required autofocus autocomplete="username" />
            <ul>
                @foreach ((array) $errors->get('email') as $message)
                    <li class="text-light">{{ $message }}</li>
                @endforeach
            </ul>
        </div>

        <!-- Password -->
        <div class="mt-4">
            {{-- <label for="password">{{ __('Password') }}</label> --}}
            <input class="form-control" id="password" type="password" name="password" placeholder="{{ __('Password') }}"
                required autocomplete="current-password" />
            <ul>
                @foreach ((array) $errors->get('password') as $message)
                    <li class="text-light">{{ $message }}</li>
                @endforeach
            </ul>
        </div>
        <div>
            <div class="d-flex justify-content-center">
                <button class="btn btn-primary w-100">
                    {{ __('Login') }}
                </button>
            </div>
            <div class="my-2">
                <p class="text-light"><strong>Dont have an account? Click </strong><a href="/register">Here</a></p>
            </div>
        </div>
    </form>
@endsection
