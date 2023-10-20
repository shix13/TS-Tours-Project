@extends('layouts.driver-app')

@section('title')
    Active Task
@endsection

@section('content')
<div class="container">
    <h3 style="color: white">ACTIVE TASK</h3>
    @if($activeTask)
    <div class="card">
        <div class="card-header">
            @php
                $startDateString = $activeTask->booking->startDate;
                $endDateString = $activeTask->booking->endDate;
                
                $startDateCarbon = \Carbon\Carbon::parse($startDateString);
                $startDate = $startDateCarbon->format('F, j, Y g:i A');

                $endDateCarbon = \Carbon\Carbon::parse($endDateString);
                $endDate = $endDateCarbon->format('F, j, Y');
            @endphp
            <h5 class="card-title">{{ $activeTask->booking->tariff->location }} · {{ $startDate }}</h5>
            <span style="background-color: orange; border-radius:5px;padding:5px">Rent ID:<strong> {{$activeTask->rentID}}</strong> | Booking ID : <strong> {{$activeTask->booking->reserveID}} </strong></span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label style="color: black;"><i class="fa-solid fa-user"></i> Customer Name</label>
                        <input style="color: black;background-color: rgb(255, 255, 255)" type="text" class="form-control" value='{{ $activeTask->booking->cust_first_name }} {{ $activeTask->booking->cust_last_name }}' readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label style="color: black;"><i class="fa-solid fa-phone"></i> Contact Number</label>
                        <input type="text" style="color: black;background-color: rgb(255, 255, 255)" class="form-control" value="{{ $activeTask->booking->mobileNum }}" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label style="color: black;"><i class="fa-solid fa-location-dot"></i> Travel Location</label>
                        <input style="color: black; background-color: white" type="text" class="form-control" name="tariff_id" value="{{ $activeTask->booking->tariff->location }}" readonly>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label style="color: black;"><i class="fa-solid fa-location-dot"></i> Rate</label>
                        <input style="color: black; background-color: white" type="text" class="form-control" name="tariff_id" 
                            @if($activeTask->booking->bookingType === 'Rent')
                            value="{{ $activeTask->booking->tariff->rate_Per_Day }}"
                            
                            @else
                            value="{{ $activeTask->booking->tariff->do_pu }}"
                            @endif
                            readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label style="color: black;"><i class="fa-solid fa-calendar-days"></i> Schedule Date</label>
                        <input type="date" style="color: black; background-color: white" class="form-control" name="pickup_date" value="{{ $startDateCarbon->format('F, j, Y') }}" readonly>
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label style="color: black;"><i class="fa-regular fa-clock"></i> Pick-Up Time</label>
                        <input type="time" style="color: black; background-color: white" class="form-control" name="pickup_time" value="{{ $startDateCarbon->format('H:i') }}" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label style="color: black;"><i class="fa-solid fa-calendar"></i> Drop-Off Date</label>
                        <input type="date" style="color: black; background-color: white" class="form-control" name="dropoff_date" value="{{ $endDateCarbon->format('F, j, Y') }}" readonly>
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label style="color: black;"><i class="fa-solid fa-clock"></i> Drop-Off Time</label>
                        <input type="time" style="color: black; background-color: white" class="form-control" name="dropoff_time" value="{{ $endDateCarbon->format('H:i') }}" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label style="color: black;"><i class="fa-solid fa-map-pin"></i> Pick-Up Address</label>
                        <input type="text" class="form-control" name="pickup_address" value="{{$activeTask->booking->pickUp_Address}}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label style="color: black;"><i class="fa-solid fa-note-sticky"></i> Note</label>
                        <textarea class="form-control" rows="4" name='note' value="{{$activeTask->booking->note}}"></textarea>
                    </div>
                </div>
            </div>
            

            <h5 class="card-title">Vehicle Assigned</h5>
            <div class="row">
                
            </div>
            @foreach($activeTask->assignments as $assignment)
                Vehicle: {{ $assignment->vehicle->unitName}} - {{ $assignment->vehicle->registrationNumber }} <br/>
            @endforeach
        </div>
    </div>
    @else
        <p>There is no active task</p>
    @endif
</div>

<script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
@endsection
