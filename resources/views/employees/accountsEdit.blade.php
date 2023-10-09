@extends('layouts.employee-app')

@section('title')
    Edit Employee
@endsection

@section('content')
<div class="container" >
    <div class="row justify-content-center">
        <div class="col-md-8" >
            <div class="card" >
                <div class="card-header" style="background-color: #122E50;color:white;">{{ __('Employee Edit Account Information') }}</div>

                <div class="card-body" style="background-color: #dde5ee;color:rgb(0, 0, 0);">
                    <form method="POST" action="{{ route('employee.update',$employee->empID) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label for="ProfileImage" class="col-md-4 col-form-label text-md-end">{{ __('Profile Image') }}</label>
                        
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
                            <label for="FirstName" class="col-md-4 col-form-label text-md-end">{{ __('First Name') }}</label>

                            <div class="col-md-6">
                                <input id="FirstName" type="text" class="form-control @error('FirstName') is-invalid @enderror" name="FirstName" value="{{ $employee->firstName }}" required autocomplete="FirstName" autofocus>

                                @error('FirstName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="LastName" class="col-md-4 col-form-label text-md-end">{{ __('Last Name') }}</label>

                            <div class="col-md-6">
                                <input id="LastName" type="text" class="form-control @error('LastName') is-invalid @enderror" name="LastName" value="{{ $employee->lastName }}" required autocomplete="LastName" autofocus>

                                @error('LastName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="Email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="Email" type="email" class="form-control @error('Email') is-invalid @enderror" name="Email" value="{{ $employee->email }}" required autocomplete="Email">

                                @error('Email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="MobileNum" class="col-md-4 col-form-label text-md-end">{{ __('Mobile Number') }}</label>
                            <div class="col-md-6">
                                <input id="MobileNum" type="text" class="form-control @error('MobileNum') is-invalid @enderror" name="MobileNum" value="{{ $employee->mobileNum }}" required>
                                @error('MobileNum')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="AccountType" class="col-md-4 col-form-label text-md-end">{{ __('Account Type') }}</label>
                        
                            <div class="col-md-6">
                                <select id="AccountType" class="form-control @error('AccountType') is-invalid @enderror" name="AccountType" required>
                                    @foreach(['Manager', 'Clerk', 'Driver', 'Mechanic', 'Driver Outsourced' , 'Mechanic Outsourced'] as $type)
                                        <option value="{{ $type }}" @if($type === $employee->accountType) selected @endif>{{ $type }}</option>
                                    @endforeach
                                </select>
                        
                                @error('AccountType')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update') }}
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
