@extends('layouts.app')

@section('content')
<div class="container">
<h2>BOOKING</h2>
    <div class="row">
        <div class="col">
            <h4>Vehicle Details</h4>
            <div class="col-md-3">
                <div class="card" style="width: 18rem;">
                    <img class="card-img-top" src="..." alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">{{ $vehicleData -> unitName}}</h5>
                        <p class="card-text">Pax: {{ $vehicleData -> pax}}</p>
                        <p class="card-text">Specifications: {{ $vehicleData -> specification}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <h4>Booking Details</h4>
            <form method="POST" action="{{ route('processbooking') }}">
            @csrf
            <input type="hidden" name="vehicleID" value="{{ $vehicleData -> unitID }}">
            <div class="container">
                <div class="row">
                    <div class="col">
                        Location
                    </div>
                    <div class="col">
                        <select id="location" name="location">
                            @foreach($tariffData as $t)
                            <option>{{ $t -> location }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        Schedule Date
                        <div class="row">
                            <div class="col">
                                Start Date <input type="date" name="StartDate">
                            </div>
                            <div class="col">
                                End Date <input type="date" name="EndDate">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        Phone Number
                    </div>
                    <div class="col">
                        <input name="MobileNum">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        Pickup Address
                    </div>
                    <div class="col">
                        <input name="PickUpAddress">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        Additional Notes
                    </div>
                    <div class="col">
                        <input name="Note">
                    </div>
                </div>
                <div class="row">
                    <div class="col text-center">
                        <button type="submit" class="btn btn-primary">Proceed to checkout</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-center">
                    Terms and Conditions
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection