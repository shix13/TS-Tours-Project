@extends('layouts.empbar')

@section('title')
    TS | Booking & Rent
@endsection

@section('content')
<br><br>

<div class="container" >

    <div class="row" >
        <div class="container">
            <nav class="navbar navbar-expand-md " style="background: midnightblue; font-weight: 700">
                <ul class="navbar-nav col-md-9" style="font-size: 18px">
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('employee.booking') ? 'active' : 'hi' }}" href="{{ route('employee.booking') }}">Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('employee.preapproved') ? 'active' : '' }}" href="{{ route('employee.preapproved') }}">Downpayment Review</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('employee.rental') ? 'active' : '' }}" href="{{ route('employee.rental') }}">Active Rentals</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('employee.rentalHistory') ? 'active' : '' }}" href="{{ route('employee.rentalHistory') }}">Rental History</a>
                    </li>
                </ul>
                <div class="col-md-5" style="justify-content: flex-end">
                    <div class="form-row" style="background-color: transparent; padding: 10px; border-radius: 5px;">
                        <div class="form-group col-md-7">
                            <input type="text" id="bookingIdSearch" class="form-control" placeholder="Search Booking Info" style="padding: 10px; background: white">
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        
        
    </div>
    
        @if(session('success'))
        <div class="custom-success-message alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <br>
        @endif

        @if(session('error'))
        <div class="custom-error-message alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <br>
        @endif
        
    <div class="card">
        <div class="card-header">
            <h4 class="card-title" style="font-weight: 700">Pending Bookings</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="scheduledInProgressTable" class="table table-hover table-striped" style="font-size: 12px;">
                    <thead class="text-primary font-montserrat">
                        <th class="bold-text">
                            <strong>#</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Customer Name</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Booking Type</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Contact Number</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Destination</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Pick-up Schedule</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Drop-Off Date</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Pick-up Address</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Note</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Actions</strong>
                        </th>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1; // Initialize a counter variable
                        @endphp

                        @if ($pendingBookings !== null && $pendingBookings->count() > 0)
                            @foreach ($pendingBookings as $pendingBooking)
                            @if ($pendingBooking->status === 'Pending')
                            <tr class="{{ $pendingBooking->status === 'Approved' ? 'table-success' : ($pendingBooking->status === 'Denied' ? 'table-danger' : '') }} text-center">
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $pendingBooking->cust_first_name }} {{ $pendingBooking->cust_last_name }}</td>
                                    <td style="color: {{ $pendingBooking->bookingType === 'Rent' ? 'blue' : 'green' }};font-weight:700">
                                        {{ $pendingBooking->bookingType }}
                                    </td>                                    
                                    <td>{{ $pendingBooking->mobileNum }}</td>
                                    <td>{{ $pendingBooking->tariff->location }}</td>
                                    <td>{!! \Carbon\Carbon::parse($pendingBooking->startDate)->format(' M d, Y ') !!}</td>
                                    <td>{!! \Carbon\Carbon::parse($pendingBooking->endDate)->format('M d, Y ') !!}</td>
                                    <td>{{ $pendingBooking->pickUp_Address }}</td>
                                    <td>{{ $pendingBooking->note }}</td>
                                    <td>
                                        <a href="{{ route('booking.Assign', ['bookingId' => $pendingBooking->reserveID]) }}" class="btn btn-success">
                                           <strong><i class="fas fa-check"></i> Assign</strong> 
                                        </a>
                                        <br><br>
                                        <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#denyModal{{ $pendingBooking->reserveID }}">
                                          <strong>  <i class="fa-solid fa-xmark"></i> Deny</strong>
                                        </button>                                        
                                        
                                        <!-- Deny Modal -->
                                        <div class="modal fade" id="denyModal{{ $pendingBooking->reserveID }}" tabindex="-1" role="dialog" aria-labelledby="denyModalLabel{{ $pendingBooking->reserveID }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="denyModalLabel{{ $pendingBooking->reserveID }}">Confirm Denial</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form method="POST" action="{{ route('employee.denyBooking', $pendingBooking->reserveID) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            Are you sure you want to deny this booking?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-right: 5px;">Cancel</button>
                                                            <!-- Add a submit button to trigger the denial process -->
                                                            <button type="submit" class="btn btn-danger">Deny</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div> 
                                    </td>                                    
                                    
                            </tr>
                            @endif
                            @endforeach
                        @else
                            <tr>
                                <td colspan="12">No bookings made.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                {{ $pendingBookings->links() }}
            </div>
        </div>
    </div>

    <div class="card hidden" style="left:-50px;width: 110%;">
        <div class="card-header">
            <h4 class="card-title" style="font-weight: 700">Pre-Approved Bookings</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="scheduledInProgressTable" class="table table-hover table-striped" style="font-size: 12px;">
                    <thead class="text-primary font-montserrat">
                        <th class="bold-text">
                            <strong>#</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Customer Name</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Email</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Contact Number</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Destination</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Pick-up Schedule</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Drop-Off Date</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Pick-up Address</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Note</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Status</strong>
                        </th>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1; // Initialize a counter variable
                        @endphp

                        @if ($pendingBookings !== null && $pendingBookings->count() > 0)
                            @foreach ($pendingBookings as $pendingBooking)
                            @if ($pendingBooking->status === 'Pre-Approved')
                            <tr class="{{ $pendingBooking->status === 'Pre-Approved' ? 'table-success' : ($pendingBooking->status === 'Denied' ? 'table-danger' : '') }} text-center">
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $pendingBooking->cust_first_name }} {{ $pendingBooking->cust_last_name }}</td>
                                    <td>{{ $pendingBooking->cust_email }}</td>
                                    <td>{{ $pendingBooking->mobileNum }}</td>
                                    <td>{{ $pendingBooking->tariff->location }}</td>
                                    <td>{!! \Carbon\Carbon::parse($pendingBooking->startDate)->format(' M d, Y <br>H:i A') !!}</td>
                                    <td>{!! \Carbon\Carbon::parse($pendingBooking->endDate)->format('M d, Y ') !!}</td>
                                    <td>{{ $pendingBooking->pickUp_Address }}</td>
                                    <td>{{ $pendingBooking->note }}</td>
                                    <td>{{ $pendingBooking->status }}</td>                                    
                            </tr>
                            @endif
                            @endforeach
                        @else
                            <tr>
                                <td colspan="12">No bookings made.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                {{ $pendingBookings->links() }}
            </div>
        </div>
    </div>
    
</div>
@endsection

@section('scripts')
<script>
   $(document).ready(function () {
    // Function to filter the table rows based on search query
    function filterAndSearchTable(query) {
        var table = $('#scheduledInProgressTable');
        table.find('tbody tr').each(function () {
            var row = $(this);
            var found = false;

            // Check all cells in the row for the query text
            row.find('td').each(function () {
                var cellText = $(this).text().toLowerCase();
                if (cellText.includes(query.toLowerCase())) {
                    found = true;
                    return false; // Break the loop if found in this row
                }
            });

            if (found) {
                row.show();
            } else {
                row.hide();
            }
        });
    }

    // Initial filter when the page loads
    filterAndSearchTable('');

    // Handle search input keyup/change
    $('#bookingIdSearch').on('input', function () {
        var query = $(this).val();
        // Check the conditions and show/hide rows accordingly
        if (query === '') {
            $('#scheduledInProgressTable tbody tr').show();
        } else {
            filterAndSearchTable(query);
        }
    });
});


function refreshPage() {
        location.reload(); // Reload the current page
    }

    // Call the refreshPage function every X milliseconds (e.g., every 5 seconds)
    setTimeout(refreshPage, 30000); 
</script>
@endsection

