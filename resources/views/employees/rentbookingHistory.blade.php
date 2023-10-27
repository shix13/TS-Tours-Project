@extends('layouts.empbar')

@section('title')
    TS | Booking & Rent
@endsection

@section('content')
<br><br>

<div class="container">
    <div class="row">
        <div class="container ">
            <nav class="navbar navbar-expand-md " style="background: midnightblue; font-weight: 700">
                <ul class="navbar-nav col-md-8" style="font-size: 18px">
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
                        <div class="form-group col-md-6">
                            <input type="text" id="bookingIdSearch" class="form-control" placeholder="Search Booking ID" style="padding: 10px; background: white">
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" id="statusFilter" style="padding: 8px; color: black; font-weight: 400; background: white;">
                                <option value="">All</option>
                                <option value="Scheduled">Scheduled</option>
                                <option value="Ongoing">Ongoing</option>
                                <option value="Completed">Completed</option>
                            </select>
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
        
    <div class="card ">
        <div class="card-header">
            <h4 class="card-title" style="font-weight: 700">Rentals</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table" class="table table-hover table-striped">
                    <thead class="text-primary font-montserrat">
                        <th class="bold-text">
                            <strong>#</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Booking ID</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Rent Status</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Payment Status</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Extra Hours</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Total Price</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Balance</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Actions</strong>
                        </th>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1; // Initialize a counter variable
                        @endphp

                        @if ($rents !== null && $rents->count() > 0)
                            @foreach ($rents as $rent)
                                <tr class="text-center">
                                    <td>{{ $counter++ }}</td>
                                    <td><strong>{{ $rent->reserveID }}</strong></td>
                                    <td style="color: {{ $rent->rent_Period_Status === 'In Progress' ? 'orange' : ($rent->rent_Period_Status === 'Scheduled' ? 'blue' : ($rent->rent_Period_Status === 'Ongoing' ? 'orange' : 'red')) }}">
                                        <strong>{{ $rent->rent_Period_Status }} </strong>
                                    </td>                                    
                                    <td>{{ $rent->payment_Status }}</td>
                                    <td>{{ $rent->extra_Hours !== null ? $rent->extra_Hours : '--none--' }}</td>
                                    <td>₱ {{ $rent->total_Price }}</td>
                                    <td>₱ {{ $rent->balance }}</td>

                                   
                                    <td class="col-md-1">
                                        <a href="{{ route('employee.rentalView', ['id' => $rent->reserveID]) }}" class="btn btn-success btn-block" style="margin-bottom: 10px;"><strong>View</strong></a>
                                    </td>                                   
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="12">No rentals made.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                {{ $rents -> links() }}
            </div>
        </div>
    </div>


    

    
</div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
    // Function to filter the table rows based on status
    function filterTableByStatus(status) {
        $('#table tbody tr').each(function () {
            var row = $(this);
            var rowStatusCell = row.find('td:eq(2)'); // Column index for Status
            var rowStatus = rowStatusCell.text().trim();

            if (status === '' || rowStatus === status) {
                row.show();
            } else {
                row.hide();
            }
        });
    }

    // Function to filter the table rows based on Booking ID
    function searchByBookingId(query) {
        $('#table tbody tr').each(function () {
            var row = $(this);
            var bookingId = row.find('td:eq(1)').text().toLowerCase();

            if (bookingId.includes(query.toLowerCase())) {
                row.show();
            } else {
                row.hide();
            }
        });
    }

    // Handle status filter change
    $('#statusFilter').on('change', function () {
        var status = $(this).val();
        filterTableByStatus(status);
    });

    // Handle booking ID search input
    $('#bookingIdSearch').on('input', function () {
        var query = $(this).val();
        searchByBookingId(query);
    });
});


function refreshPage() {
        location.reload(); // Reload the current page
    }

    // Call the refreshPage function every X milliseconds (e.g., every 5 seconds)
    setTimeout(refreshPage, 30000); 
    </script>
@endsection
