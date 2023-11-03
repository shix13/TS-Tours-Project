@extends('layouts.empbar')

@section('title')
    TS | Vehicle Tracking
@endsection

@section('content')
<link type="text/css" rel="stylesheet" href="https://api.mqcdn.com/sdk/mapquest-js/v1.3.2/mapquest.css"/>
<br>
<br>
<div class="container">
    <h3>Vehicle Tracking</h3>
    @if(!$activeTasks->isEmpty())
        <div class="row">
        @foreach($activeTasks as $activeTask)
            @foreach($activeTask->assignments as $assignment)
                    <div class="col-md-4">
                    @php
                        $vehicle = $assignment->vehicle;
                    @endphp
                    <div class="card">
                        <!--div class="row g-0"-->
                        <div style="height: 100px; overflow: hidden; margin: 0 auto;">
                        <img src="{{ asset('storage/' . $vehicle->pic) }}" class="card-img-top" style="width: 100%; height: 100%; object-fit: cover;" alt="Vehicle Image">
                        </div>
                            <!--div class="col-md-4">
                                <img src="{{ asset('storage/' . $vehicle->pic) }}" style="width: 100%; height: 100%; object-fit: cover;" class="img-fluid rounded-start" alt="Vehicle Image">
                            </div-->
                            <!--div class="col"-->
                                <div class="card-body">
                                    <h5 class="card-title" style="text-align:center;"><b>{{ $vehicle->unitName }} - {{ $vehicle->registrationNumber }}</b></h5>
                                    <p class="card-text">
                                        <div class="row">
                                            <div class="col" style="text-align:center">
                                                <b>Driver</b> <br/>{{ $assignment->employee->firstName }} {{ $assignment->employee->lastName }}
                                            </div>
                                            <div class="col" style="text-align:center">
                                                <b>BookingID</b> <br/>{{ $assignment->rent->reserveID }}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="geolocationData" data-assignment-id="{{ $assignment->assignedID }}">
                                            @php
                                                $location = $assignment->geolocation()->latest('created_at')->first();
                                            @endphp
                                            @if($location)
                                                @php
                                                    $dateString = $location->created_at;
                                                    
                                                    $dateCarbon = \Carbon\Carbon::parse($dateString);
                                                    $date = $dateCarbon->format('F, j, Y g:i A');
                                                @endphp
                                                Geocode: <a href="#" class="address-link" data-latitude="{{ $location->latitude }}" data-longitude="{{ $location->longitude }}">{{ $location->latitude }}, {{ $location->longitude }}</a><br/>
                                                Last Sent: {{ $date }}<br/>
                                            @else
                                                Location not yet sent
                                            @endif
                                        </div>
                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" style="max-width: 1000px; max-height: 400px;">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">View Map</h5>
                                                <a href="#" class="now-ui-icons ui-1_simple-remove" data-dismiss="modal"></a>
                                            </div>
                                            <div class="modal-body" style="display: flex; justify-content: center; align-items: center;">
                                                <!-- Content for your modal goes here -->
                                                <div id="map" style="width: 950px; height: 400px"></div>
                                            </div>
                                            </div>
                                        </div>
                                        </div>
                                    </p>
                                </div>    
                            <!--/div-->
                        <!--/div-->
                    </div>
                    </div>
            @endforeach
        @endforeach
        </div>
    @else
        <h3 style="color:black; text-align:center;">There are currently no active vehicles.</h3>
    @endif
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://api.mqcdn.com/sdk/mapquest-js/v1.3.2/mapquest.js"></script>
<script>
$(document).on('click', '.address-link', function() {
    // Extract latitude and longitude from data attributes
    var latitude = $(this).data('latitude');
    var longitude = $(this).data('longitude');

    // Open the modal
    $('#exampleModal').modal('show');

    // You can modify the content of the modal here
    var modalContent = 'Latitude: ' + latitude + '<br>' +
                      'Longitude: ' + longitude;

    L.mapquest.key = 'v91XyqNuPoLuHVjZgofPcPL2AWJu14wd';

    L.mapquest.geocoding().reverse([latitude, longitude], createMap);

    function createMap(error, response) {
        var location = response.results[0].locations[0];
        var latLng = location.displayLatLng;
        var map = L.mapquest.map('map', {
        center: latLng,
        layers: L.mapquest.tileLayer('map'),
        zoom: 16
        });

        var customIcon = L.mapquest.icons.circle({
        primaryColor: '#3b5998'
        });

        L.marker(latLng, { icon: customIcon }).addTo(map);
    }
    // Set the content inside the modal body
    //$('.modal-body').html(modalContent);
    console.log('click!');
});

function updateGeolocationData(assignmentId) {
    var geolocationContainer = $(`[data-assignment-id="${assignmentId}"]`);

    $.ajax({
        url: "{{ route('api.getLatestGeolocation') }}",
        method: "GET",
        data: { assignmentId: assignmentId }, // Send the assignment ID to the API
        success: function(data) {
            if (data.data) {
                var location = data.data;
                var dateCarbon = new Date(location.created_at);
                var options = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric',
                    second: 'numeric',
                    hour12: true
                };

                var content = 'Geocode: <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal" class="address-link" data-latitude="' + location.latitude + '" data-longitude="' + location.longitude + '">' + location.latitude + ', ' + location.longitude + '</a><br/>' +
                            'Last Sent: ' + dateCarbon.toLocaleString(undefined, options) + '<br/>';
                geolocationContainer.html(content);
                console.log("update!");
                /*
                L.mapquest.key = 'v91XyqNuPoLuHVjZgofPcPL2AWJu14wd';
                var geocoder = L.mapquest.geocoding();
                geocoder.reverse([location.latitude, location.longitude], displayReverseGeocodedAddress);

                function displayReverseGeocodedAddress(error,result) {
                    var content = 'Geocode: ' + location.latitude + ', ' + location.longitude + '<br/>';
                                //'Last Sent: ' + dateCarbon.toLocaleString(undefined, options) + '<br/>';

                    if (result) {
                        // Extract the address from the reverse geocoding results
                        var addressData = result.results[0].locations[0];
                        var street = addressData.street || '';
                        var adminArea6 = addressData.adminArea6 || '';
                        var adminArea5 = addressData.adminArea5 || '';
                        var adminArea4 = addressData.adminArea4 || '';
                        
                        var address = `${street}, ${adminArea6}, ${adminArea5}, ${adminArea4}`;
                        
                        content += 'Address: ' + address + '<br/>' +
                            'Last Sent: ' + dateCarbon.toLocaleString(undefined, options) + '<br/>' +
                            '<a href="#" class="address-link" data-address="' + address + '">View Map</a>';
                    } else {
                        content += 'Last Sent: ' + dateCarbon.toLocaleString(undefined, options) + '<br/>';
                    }

                    geolocationContainer.html(content);
                    console.log("update!");
                    console.log(result);
                }
                */
            } else {
                geolocationContainer.html('Location not yet sent');
            }
        }
    });
}

// Call updateGeolocationData for each assignment individually upon load
$('.geolocationData').each(function() {
    var assignmentId = $(this).data('assignment-id');
    window.addEventListener('load', updateGeolocationData(assignmentId));
});

// Call updateGeolocationData for each assignment individually every 10 seconds
$('.geolocationData').each(function() {
    var assignmentId = $(this).data('assignment-id');
    setInterval(function() {
        updateGeolocationData(assignmentId);
    }, 10000); // Adjust the interval as needed
});
/*
// Call reverseGeocode function for each assignment individually
$('.geolocationData').each(function() {
    var assignmentId = $(this).data('assignment-id');
    setInterval(function() {
        updateGeolocationData(assignmentId);
    }, 5000); // Adjust the interval as needed
});

L.mapquest.key = 'v91XyqNuPoLuHVjZgofPcPL2AWJu14wd';

    L.mapquest.geocoding().reverse([latitude, longitude], createMap);

    function createMap(error, response) {
        var location = response.results[0].locations[0];
        var latLng = location.displayLatLng;
        var map = L.mapquest.map('map', {
        center: latLng,
        layers: L.mapquest.tileLayer('map-container'),
        zoom: 16
        });

        var customIcon = L.mapquest.icons.circle({
        primaryColor: '#3b5998'
        });

        L.marker(latLng, { icon: customIcon }).addTo(map);
    }
*/
</script>
@endsection

