@extends('layouts.index')

@section('title')
    Edit Employee
@endsection

@section('content')
<body style="background: radial-gradient(circle, rgba(234,235,238,1) 0%, rgba(226,228,231,1) 23%, rgba(211,211,224,1) 50%, rgba(221,221,232,1) 79%, rgba(205,207,209,1) 100%);text-align:left">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header" style="background-color: #122E50; color: white; padding: 10px; font-size: 20px; font-weight: 700; text-align: center">{{ __('Edit Employee Information') }}</div>

                    <div class="card-body" style="background-color: #ffffffa9; color: rgb(0, 0, 0);">
                        <form method="POST" action="{{ route('employee.update', $employee->empID) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label for="ProfileImage" class="col-md-4 col-form-label text-md-end"><i class="fas fa-image"></i> {{ __('Profile Image') }}</label>

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
                                <label for="FirstName" class="col-md-4 col-form-label text-md-end"><i class="fas fa-user"></i> {{ __('First Name') }}</label>

                                <div class="col-md-6">
                                    <input id="FirstName" type="text" class="form-control @error('FirstName') is-invalid @enderror" name="FirstName" value="{{ $employee->firstName }}" required autocomplete="FirstName" autofocus required>

                                    @error('FirstName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="LastName" class="col-md-4 col-form-label text-md-end"><i class="fas fa-user"></i> {{ __('Last Name') }}</label>

                                <div class="col-md-6">
                                    <input id="LastName" type="text" class="form-control @error('LastName') is-invalid @enderror" name="LastName" value="{{ $employee->lastName }}" required autocomplete="LastName" autofocus required>

                                    @error('LastName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="Email" class="col-md-4 col-form-label text-md-end"><i class="fas fa-envelope"></i> {{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="Email" type="email" class="form-control @error('Email') is-invalid @enderror" name="Email" value="{{ $employee->email }}" required autocomplete="Email" required>

                                    @error('Email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="MobileNum" class="col-md-4 col-form-label text-md-end"><i class="fas fa-phone"></i> {{ __('Mobile Number') }}</label>
                                <div class="col-md-6">
                                    <input id="MobileNum" type="text" class="form-control @error('MobileNum') is-invalid @enderror" name="MobileNum" value="{{ $employee->mobileNum }}" required required>
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
                                        @foreach(['Manager', 'Clerk', 'Driver', 'Mechanic', 'Driver Outsourced', 'Mechanic Outsourced'] as $type)
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
                                        <i class="fas fa-save"></i> {{ __('Update') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
@endsection

