@extends('layouts.driver-app')

@section('title')
    Upcoming Tasks
@endsection

@section('content')
<div class="container">
    @if(!$upcomingTasks->isEmpty())
    <h3 style="color: white">UPCOMING TASKS</h3>
        @foreach($upcomingTasks as $task)
            <div class="card">
                <div class="card-body">
                    @php
                        $startDateString = $task->booking->startDate;
                        $endDateString = $task->booking->endDate;
                        
                        $startDateCarbon = \Carbon\Carbon::parse($startDateString);
                        $startDate = $startDateCarbon->format('F, j, Y g:i A');

                        $endDateCarbon = \Carbon\Carbon::parse($endDateString);
                        $endDate = $endDateCarbon->format('F, j, Y');
                    @endphp
                    
                    <h5 class="card-title">{{ $task->booking->tariff->location }}, {{ $startDate }}</h5>
                    End date: {{ $endDate }}<br/>
                    <hr>
                    Pick-up at: {{ $task->booking->pickUp_Address}}<br/>

                    @foreach($task->assignments as $assignment)
                        Vehicle: {{ $assignment->vehicle->unitName}} - {{ $assignment->vehicle->registrationNumber }} <br/>
                    @endforeach

                    <!-- Button to trigger the modal for this task -->
                    <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#taskModal{{$task->rentID}}">
                        View Details
                    </button>
                </div>
            </div>

            <!-- Modal for this task -->
            <div class="modal fade" id="taskModal{{$task->rentID}}" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel{{$task->rentID}}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="taskModalLabel{{$task->id}}">{{ $task->booking->tariff->location }}, {{ $startDate }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <span style="background-color: orange; border-radius:5px;padding:5px">Rent ID:<strong> {{$task->rentID}}</strong> | Booking ID : <strong> {{$task->booking->reserveID}} </strong></span>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label style="color: black;"><i class="fa-solid fa-user"></i> Customer Name</label>
                                        <input style="color: black;background-color: rgb(255, 255, 255)" type="text" class="form-control" value='{{ $task->booking->cust_first_name }} {{ $task->booking->cust_last_name }}' readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label style="color: black;"><i class="fa-solid fa-phone"></i> Contact Number</label>
                                        <input type="text" style="color: black;background-color: rgb(255, 255, 255)" class="form-control" value="{{ $task->booking->mobileNum }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label style="color: black;"><i class="fa-solid fa-location-dot"></i> Travel Location</label>
                                        <input style="color: black; background-color: white" type="text" class="form-control" name="tariff_id" value="{{ $task->booking->tariff->location }}" readonly>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label style="color: black;"><i class="fa-solid fa-location-dot"></i> Rate</label>
                                        <input style="color: black; background-color: white" type="text" class="form-control" name="tariff_id" 
                                            @if($task->booking->bookingType === 'Rent')
                                            value="{{ $task->booking->tariff->rate_Per_Day }}"
                                            
                                            @else
                                            value="{{ $task->booking->tariff->do_pu }}"
                                            @endif
                                            readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label style="color: black;"><i class="fa-solid fa-calendar-days"></i> Schedule Date</label>
                                        <input style="color: black; background-color: white" class="form-control" name="pickup_date" value="{{ $startDateCarbon->format('F, j, Y') }}" readonly>
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
                                <div class="col">
                                    <div class="form-group">
                                        <label style="color: black;"><i class="fa-solid fa-map-pin"></i> Pick-Up Address</label>
                                        <input style="color: black; background-color: white" type="text" class="form-control" name="pickup_address" value="{{$task->booking->pickUp_Address}}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label style="color: black;"><i class="fa-solid fa-calendar"></i> Drop-Off Date</label>
                                        <input style="color: black; background-color: white" class="form-control" name="dropoff_date" value="{{ $endDateCarbon->format('F, j, Y') }}" readonly>
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
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label style="color: black;"><i class="fa-solid fa-note-sticky"></i> Note</label>
                                        <textarea class="form-control" style="color: black; background-color: white" rows="4" name='note' readonly>{{$task->booking->note}}</textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <h5 class="card-title">Vehicle Assigned</h5>
                                @foreach($task->assignments as $assignment)
                                    @php
                                    $vehicle = $assignment->vehicle;
                                    @endphp
                                    <div class="card">
                                        <div class="row g-0">
                                            <div class="col-md-4">
                                                <img src="{{ asset('storage/' . $vehicle->pic) }}" class="img-fluid rounded-start" alt="Vehicle Image">
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{ $vehicle->unitName }} - {{ $vehicle->registrationNumber }}</h5>
                                                    <p class="card-text">
                                                        Year Model: {{ $vehicle->yearModel }}<br/>
                                                        Color: {{ $vehicle->color }}<br/>
                                                        Pax: {{ $vehicle->pax }}<br/>
                                                    </p>
                                                </div>    
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            <div class="row">
                                <div class="col">
                                    <form method="POST" action="{{ route('driver.start') }}">
                                        @csrf
                                        <input type="hidden" id="rentID" name="rentID" value="{{ $task->rentID }}">
                                        @if($isActiveTask)
                                            <button class="btn btn-primary btn-lg btn-block" type="submit" disabled>
                                                Start Rent
                                            </button>
                                        @else
                                            <button class="btn btn-primary btn-lg btn-block" type="submit">
                                                Start Rent
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-lg btn-block" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <h3 style="color: white; text-align: center">You have no upcoming task</h3>
    @endif
</div>

<script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
@endsection