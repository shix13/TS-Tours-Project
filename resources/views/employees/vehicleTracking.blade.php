@extends('layouts.empbar')

@section('title')
    TS | Vehicle Tracking
@endsection

@section('content')
<br>
<br>
<div class="container">
    <div id="refreshDiv">
    @foreach($assignments as $assignment)
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
                            Driver: {{ $assignment->employee->firstName }} {{ $assignment->employee->lastName }}<br/>
                            RentID: {{ $assignment->rent->rentID }}<br/>
                            <hr>
                            @php
                                $location = $assignment->geolocation()->latest('created_at')->first();
                            @endphp
                            @if($location)
                                @php
                                    $dateString = $location->created_at;
                                    
                                    $dateCarbon = \Carbon\Carbon::parse($dateString);
                                    $date = $dateCarbon->format('F, j, Y g:i A');
                                @endphp
                                Latitude: {{ $location->latitude }} <br/>
                                Longitude: {{ $location->longitude }} <br/> 
                                Last Sent: {{ $date }}<br/>
                            @else
                                Location not yet sent
                            @endif
                        </p>
                    </div>    
                </div>
            </div>
        </div>
    @endforeach
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Refresh the page every 10 seconds (10000 milliseconds)
    setInterval(function() {
        location.reload();
    }, 10000);
</script>
@endsection