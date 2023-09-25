@extends('layouts.empbar')

@section('title')
    TS | Add New Vehicle Type
@endsection

@section('content')
<br><br>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Add New Vehicle Type</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('vehicleTypes.store') }}">
                        @csrf

                        <div class="form-group">
                            <label for="vehicleType">Vehicle Type</label>
                            <input type="text" name="vehicle_type" id="vehicleType" class="form-control @error('vehicle_type') is-invalid @enderror" required>
                            @error('vehicle_type')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Add Vehicle Type</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
