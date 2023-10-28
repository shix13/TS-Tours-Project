@extends('layouts.empbar')

@section('title')
    TS | Vehicles
@endsection

@section('content')
<br>
<br>
<div class="container">
    @foreach($assignments as $assignment)
        @php
            $vehicle = $assignment->vehicle;
        @endphp
        <div class="card">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="{{ $vehicle->pic}}" class="img-fluid rounded-start" alt="Vehicle Image">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">{{ $vehicle->unitName }}, {{ $vehicle->registrationNumber }}</h5>
                        <p class="card-text">
                            Driver: {{ $assignment->employee->firstName }} {{ $assignment->employee->lastName }}<br/>
                            Location: <br/>
                        </p>
                    </div>    
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection