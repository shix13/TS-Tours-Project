@extends('layouts.empbar')

@section('title')
   TS | Dashboard
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<link href="{{ asset('assets/css/employee-dashboard.css') }}" rel="stylesheet" />
<div class="content">
    <br><br>
<div class="container">
    
    
    <div class="row">
        <div class="col">
            <div class="card-box bg-darkblu">
                <div class="inner">
                    <h3> See Vehicles </h3>
                    <p> Listed Vehicles, Add Vehicle, Edit Vehicle, Remove Vehicle </p>
                </div>
                <div class="icon">
                    <i class="fa fa-car" aria-hidden="true"></i>
                </div>
                <a href="{{ route('employee.vehicle') }}" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col">
            <div class="card-box bg-darkblu">
                <div class="inner">
                    <h3> See Accounts </h3>
                    <p> Listed Account & Types, Account Management </p>
                </div>
                <div class="icon">
                    <i class="fa fa-users" aria-hidden="true"></i>
                </div>
                @if(Auth::guard('employee')->check() && Auth::guard('employee')->user()->accountType == 'Manager')
                <a href="{{ route('employee.accounts') }}" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card-box bg-darkblu">
                <div class="inner">
                    <h3> See Tariff </h3>
                    <p> Price per day Rates based on location </p>
                </div>
                <div class="icon">
                    <i class="fa fa-clipboard" aria-hidden="true"></i>
                </div>
                <a href="{{ route('employee.tariff') }}" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col">
            <div class="card-box bg-darkblu">
                <div class="inner">
                    <h3> See Maintenance </h3>
                    <p> Listed Maintenance, Scheduling of Vehicle Maintenance </p>
                </div>
                <div class="icon">
                    <i class="fa fa-wrench" aria-hidden="true"></i>
                </div>
                <a href="{{ route('employee.maintenance') }}" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card-box bg-darkblu">
                <div class="inner">
                    <h3> See Booking & Rental </h3>
                    <p> Booking Confirmation, Schedule of Approved Rentals </p>
                </div>
                <div class="icon">
                    <i class="fa fa-book" aria-hidden="true"></i>
                </div>
                <a href="{{ route('employee.booking') }}" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col">
            <div class="card-box bg-darkblu">
                <div class="inner">
                    <h3> See Remittance </h3>
                    <p> Listed Remittance history, Add new remittance for rent </p>
                </div>
                <div class="icon">
                    <i class="fa fa-money" aria-hidden="true"></i>
                </div>
                <a href="{{ route('employee.remittance') }}" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
</div>
<hr>
<h2 class="text-center" style="margin-top:5%">CALENDAR SCHEDULE</h2>
<div class="mx-auto" id="calendar" style="width: 90%;"></div>
    <hr>
</div>
@endsection

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/daygrid/main.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/daygrid/main.js"></script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: {!! json_encode($formattedSchedules) !!}, // Convert to JSON
        eventRender: function(info) {
            if (info.event.extendedProps.type === 'maintenance') {
                info.el.style.backgroundColor = 'blue'; // Set the background color for maintenance events
            }
        },
        eventClick: function(info) {
            if (info.event.extendedProps.type === 'booking') {
                // Handle booking event
                alert(
                    info.event.title + '\n------------------'+
                    '\nOwnership Type:' + info.event.extendedProps.ownershipType +
                    '\nBooking Type: ' + info.event.extendedProps.bookingType +
                    '\nBooking Number: ' + info.event.extendedProps.trackingID +
                    '\nDate Range: ' + info.event.extendedProps.dateRange+
                    '\nBooking Status: ' + info.event.extendedProps.bookingstatus +
                    '\nRent Status: ' + info.event.extendedProps.rentstatus 
                );

            } else if (info.event.extendedProps.type === 'maintenance') {
                // Handle maintenance event
                alert(
                    info.event.title  + '\n------------------'+
                    '\nStatus: ' + info.event.extendedProps.status 
                );
            }
        }
    });
    calendar.render();
});



</script>
@endsection