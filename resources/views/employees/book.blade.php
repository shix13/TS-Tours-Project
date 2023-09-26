@extends('layouts.empbar')

@section('title')
    TS | Booking & Rent
@endsection

@section('content')
<br><br>

<div class="container" >

    <div class="row" >
        <div class="container">
            <nav class="navbar navbar-expand-md " style="background: midnightblue;font-weight:700">
                <ul class="navbar-nav" style="font-size: 18px">
                    <li class="nav-item" >
                        <a class="nav-link" href="{{ route('employee.booking') }}">Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('employee.preapproved') }}">Downpayment Review</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('employee.rental') }}">Rentals</a>
                    </li>
                </ul>
                <div class="col-md-7" style="justify-content:flex-end">
                    <div class="form-row" style="background-color: transparent; padding: 10px; border-radius: 5px; ">
                        <div class="form-group col-md-6">
                            <input type="text" id="search" class="form-control" placeholder="Search booking" onkeyup="searchAndFilter()" style="padding: 10px;background:white">
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" id="statusFilter" style="padding: 8px;color:black;font-weight:400;background:white" >
                                <option value="">All</option>
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Denied">Denied</option>
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
        
    <div class="card " style="left:-50px;width: 110%;">
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
                                    <td>{{ $pendingBooking->cust_email }}</td>
                                    <td>{{ $pendingBooking->mobileNum }}</td>
                                    <td>{{ $pendingBooking->tariff->location }}</td>
                                    <td>{!! \Carbon\Carbon::parse($pendingBooking->startDate)->format(' M d, Y <br>H:i A') !!}</td>
                                    <td>{!! \Carbon\Carbon::parse($pendingBooking->endDate)->format('M d, Y ') !!}</td>
                                    <td>{{ $pendingBooking->pickUp_Address }}</td>
                                    <td>{{ $pendingBooking->note }}</td>
                                    <td>{{ $pendingBooking->status }}</td>
                                    <td>
                                        <a href="{{ route('booking.Assign', ['bookingId' => $pendingBooking->reserveID]) }}" class="btn btn-success">Assign</a>
                                        <br><br>
                                        <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#denyModal{{ $pendingBooking->reserveID }}">
                                            <strong>Deny</strong>
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
    /*
    function searchAndFilter() {
        // Get the input value and selected status from the filter
        var searchText = document.getElementById('search').value.toLowerCase();
        var selectedStatus = document.getElementById('statusFilter').value;
        var table = document.getElementById('table');
        var rows = table.getElementsByTagName('tr');

        // Loop through all table rows, and show/hide them based on conditions
        for (var i = 1; i < rows.length; i++) {
            var row = rows[i];
            var columns = row.getElementsByTagName('td');
            var statusCell = row.getElementsByTagName('td')[12]; // Adjust the index based on your table structure
            var found = false;

            for (var j = 0; j < columns.length; j++) {
                var cell = columns[j];
                if (cell) {
                    var text = cell.textContent.toLowerCase();
                    if (text.indexOf(searchText) > -1) {
                        found = true;
                        break;
                    }
                }
            }

            if (
                (searchText === '' && selectedStatus === '') ||
                (searchText === '' && selectedStatus !== '' && statusCell && statusCell.textContent.trim() === selectedStatus) ||
                (searchText !== '' && selectedStatus === '') ||
                (searchText !== '' && selectedStatus !== '' && statusCell && statusCell.textContent.trim() === selectedStatus)
            ) {
                if (found) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            } else {
                row.style.display = 'none';
            }
        }
    }
    */

    $(document).ready(function () {
    // Function to filter the table rows based on status and search query
    function filterAndSearchTable(tableId, status, query) {
        var table = $('#' + tableId);
        table.find('tbody tr').each(function () {
            var row = $(this);
            var rowStatusCell = row.find('td:eq(12)'); // Update the column index for Status
            var rowStatus = rowStatusCell.text().trim();
            var found = false;

            // Condition 1: Status is null and query is null
            if (status === '' && query === '') {
                found = true;
            } 
            // Condition 2: Status is null and there is a query
            else if (status === '' && query !== '') {
                if (row.text().toLowerCase().includes(query.toLowerCase())) {
                    found = true;
                }
            } 
            // Condition 3: Status has a value and query is null
            else if (status !== '' && query === '') {
                if (rowStatus === status) {
                    found = true;
                }
            } 
            // Default condition: Both status and query have values
            else {
                if ((status === '' || rowStatus === status) && (query === '' || row.text().toLowerCase().includes(query.toLowerCase()))) {
                    found = true;
                }
            }

            if (found) {
                row.show();
            } else {
                row.hide();
            }
        });
    }

    // Initial filter when the page loads
    filterAndSearchTable('scheduledInProgressTable', '', '');
    filterAndSearchTable('completedCancelledTable', '', '');

    // Handle filter button change and search input keyup/change
    $('#statusFilter, #search').on('input', function () {
        var status = $('#statusFilter').val();
        var query = $('#search').val();

        // Check the conditions and show/hide tables and rows accordingly
        if (status === '' && query === '') {
            $('#scheduledInProgressTable').show();
            $('#completedCancelledTable').show();
            $('#scheduledInProgressTable tbody tr').show();
            $('#completedCancelledTable tbody tr').show();
        } else if (status === '' && query !== '') {
            $('#scheduledInProgressTable').show();
            $('#completedCancelledTable').show();
            filterAndSearchTable('scheduledInProgressTable', status, query);
            filterAndSearchTable('completedCancelledTable', status, query);
        } else if (status !== '' && query === '') {
            $('#scheduledInProgressTable').hide();
            $('#completedCancelledTable').hide();
            filterAndSearchTable('scheduledInProgressTable', status, query);
            filterAndSearchTable('completedCancelledTable', status, query);
            $('#' + (status === 'Approved' || status === 'Cancelled' || status === 'Denied' ? 'completedCancelledTable' : 'scheduledInProgressTable')).show();
            $('#completedCancelledTable, #scheduledInProgressTable').not('#' + (status === 'Approved' || status === 'Cancelled' || status === 'Denied' ? 'completedCancelledTable' : 'scheduledInProgressTable')).hide();
        } else {
            filterAndSearchTable('scheduledInProgressTable', status, query);
            filterAndSearchTable('completedCancelledTable', status, query);
            $('#scheduledInProgressTable, #completedCancelledTable').hide();
            $('#' + (status === 'Approved' || status === 'Cancelled' || status === 'Denied' ? 'completedCancelledTable' : 'scheduledInProgressTable')).show();
            $('#completedCancelledTable, #scheduledInProgressTable').not('#' + (status === 'Approved' || status === 'Cancelled' || status === 'Denied' ? 'completedCancelledTable' : 'scheduledInProgressTable')).hide();
        }
    });
});
    
</script>

@endsection
