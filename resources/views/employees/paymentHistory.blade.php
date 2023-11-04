@extends('layouts.empbar')

@section('title')
    TS | Booking & Rent
@endsection

@section('content')
<br><br>

<div class="container">
    <div class="row">
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
                            <input type="text" id="bookingIdSearch" class="form-control" placeholder="Search Booking ID" style="padding: 10px; background: white">
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>
        
    <div class="card">
        <div class="card-header">
            <div class="btn-group" role="group" aria-label="Button Group">
                <a href="{{ route('employee.preapproved') }}" class="btn btn-primary ">Pre-approved Booking</a>
                <a href="{{ route('employee.paymentHistory') }}" class="btn btn-info ">Payment History</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="scheduledInProgressTable" class="table table-hover table-striped">
                    <thead class="text-primary font-montserrat">
                        <tr>
                            <th class="bold-text"><strong>#</strong></th>
                            <th class="bold-text"><strong>Booking ID</strong></th>
                            <th class="bold-text"><strong>Customer Name</strong></th>
                            <th class="bold-text"><strong>Total Amount</strong></th>
                            <th class="bold-text"><strong>Downpayment Paid</strong></th>
                            <th class="bold-text"><strong>Gcash Reference No.</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1; // Initialize a counter variable
                        @endphp

                        @if ($paidBookings !== null && $paidBookings->count() > 0)
                            @foreach ($paidBookings as $paidBooking)
                                <tr class="text-center">
                                    <td>{{ $counter++ }}</td>
                                    <td><strong>{{ $paidBooking->reserveID }}</strong></td>
                                    <td>{{ $paidBooking->cust_first_name }} {{ $paidBooking->cust_first_name }}</td>
                                    <td>₱ {{ $paidBooking->subtotal }}</td>
                                    <td>₱ {{ $paidBooking->downpayment_Fee }}</td>
                                    <td>{{ $paidBooking->gcash_RefNum }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="12">No payments made.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
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
            var bookingId = row.find('td:eq(1)').text().trim(); // Get Booking ID from the second table cell

            // Check if Booking ID includes the query text
            if (bookingId.includes(query.toLowerCase())) {
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
