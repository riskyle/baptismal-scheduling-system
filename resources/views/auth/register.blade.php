@extends('layouts.auth')
@section('content')
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <h3 class="text-center mb-5 text-light">Register!</h3>
        <div class="d-grid justify-content-center">
            <!-- Name -->
            <div class="d-inline-flex">
                <div class="m-3">
                    <input class="form-control" id="name" type="text" name="fname"
                        placeholder="{{ __('Firstname') }} " value="{{ old('fname') }}" required autofocus
                        autocomplete="name" />
                    <ul>
                        @foreach ((array) $errors->get('fname') as $message)
                            <li class="text-light">{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="m-3">
                    <input class="form-control" id="name" type="text" name="mname"
                        placeholder="{{ __('Middlename') }} " value="{{ old('mname') }}" autofocus autocomplete="name" />
                    <ul>
                        @foreach ((array) $errors->get('mname') as $message)
                            <li class="text-light">{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="m-3">
                    <input class="form-control" id="name" type="text" name="lname"
                        placeholder="{{ __('Lastname') }} " value="{{ old('lname') }}" required autofocus
                        autocomplete="name" />
                    <ul>
                        @foreach ((array) $errors->get('lname') as $message)
                            <li class="text-light">{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Email Address -->
            <div class="d-inline-flex">
                <div class="m-3">
                    {{-- <label for="email"> {{ __('Email') }} </label> --}}
                    <input class="form-control" id="email" type="email" name="email"
                        placeholder="{{ __('Email') }} " value="{{ old('email') }}" required autofocus
                        autocomplete="email" />
                    <ul>
                        @foreach ((array) $errors->get('email') as $message)
                            <li class="text-light">{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>

                <!-- Password -->
                <div class="m-3">
                    {{-- <label for="password"> {{ __('Password') }} </label> --}}
                    <input class="form-control" id="password" type="password" name="password"
                        placeholder="{{ __('Password') }} " required autofocus autocomplete="password" />
                    <ul>
                        @foreach ((array) $errors->get('password') as $message)
                            <li class="text-light">{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>

                <!-- Confirm Password -->
                <div class="m-3">
                    {{-- <label for="password_confirmation"> {{ __('Confirm Password') }} </label> --}}
                    <input class="form-control" id="password_confirmation" type="password" name="password_confirmation"
                        placeholder="{{ __('Confirm Password') }} " required autofocus autocomplete="new-password" />
                    <ul>
                        @foreach ((array) $errors->get('password_confirmation') as $message)
                            <li class="text-light">{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="d-grid text-center">
            <div class="d-flex justify-content-center">
                <button class="btn btn-primary w-25">
                    {{ __('Register') }}
                </button>
            </div>
            <div class="d-inline text-light">
                {{ __('Already have an account? ') }}
                <a class="bg-light " href="{{ route('login') }}">
                    {{ __('login') }}
                </a>
            </div>
        </div>
    </form>
@endsection
