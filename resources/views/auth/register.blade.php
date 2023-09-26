@extends('layouts.index')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container" >
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card"  style="border-radius: 30px">
                <h1 class="card-header" style="font-size: 30px"><strong>Account Registration</strong></h1>
                <hr>
                <div class="card-body" style="color:rgb(0, 0, 0);">
                    <form method="POST" action="{{ route('employee.register.submit') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <label for="ProfileImage" class="col-md-4 col-form-label text-md-end">  <i class="fas fa-image"></i>  {{ __('Profile Image') }}</label>
                        
                            <div class="col-md-6">
                                <input id="ProfileImage" type="file" class="form-control @error('ProfileImage') is-invalid @enderror" name="ProfileImage" accept="image/*">
                        
                                @error('ProfileImage')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="FirstName" class="col-md-4 col-form-label text-md-end"> <i class="fas fa-user"></i> {{ __('First Name') }}</label>

                            <div class="col-md-6">
                                <input id="FirstName" type="text" class="form-control @error('FirstName') is-invalid @enderror" name="FirstName" value="{{ old('FirstName') }}" required autocomplete="FirstName" autofocus required>

                                @error('FirstName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="LastName" class="col-md-4 col-form-label text-md-end"> <i class="fas fa-user"></i> {{ __('Last Name') }}</label>

                            <div class="col-md-6">
                                <input id="LastName" type="text" class="form-control @error('LastName') is-invalid @enderror" name="LastName" value="{{ old('LastName') }}" required autocomplete="LastName" autofocus required>

                                @error('LastName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end"> <i class="fas fa-envelope"></i> {{ __('Email Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        

                        <div class="row mb-3">
                            <label for="MobileNum" class="col-md-4 col-form-label text-md-end"><i class="fas fa-phone"></i> {{ __('Mobile Number') }}</label>
                            <div class="col-md-6">
                                <input id="MobileNum" type="text" class="form-control @error('MobileNum') is-invalid @enderror" name="MobileNum" value="{{ old('MobileNum') }}" required required>
                                @error('MobileNum')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="AccountType" class="col-md-4 col-form-label text-md-end"><i class="fas fa-user-tag"></i> {{ __('Account Type') }}</label>

                            <div class="col-md-6">
                                <select id="AccountType" class="form-control @error('AccountType') is-invalid @enderror" name="AccountType" required> 
                                    <option value="">--Select Account Type--</option>
                                    <option value="Manager">Manager</option>
                                    <option value="Clerk">Clerk</option>
                                    <option value="Driver">Driver</option>
                                    <option value="Mechanic">Mechanic</option>
                                </select>

                                @error('AccountType')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end"><i class="fas fa-lock"></i> {{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end"><i class="fas fa-lock"></i> {{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>  
            </div>
        </div>
    </div>
</div>
@endsection
