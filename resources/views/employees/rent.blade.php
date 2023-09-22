@extends('layouts.empbar')

@section('title')
    TS | Booking & Rent
@endsection

@section('content')
<br><br>

<div class="container">
    <div class="row " >
        <div class="col-md-0">
            <a href="{{ route('employee.booking') }}" class="btn btn-danger" style="padding:25px 30px 25px 30px;margin-left:15px;margin-top:0%"><strong>View Bookings</strong></a>
        </div>
        <div class="col-md-8" style="justify-content:flex-end">
            <div class="form-row" style="background-color: hsla(0, 0%, 100%, 0.7); padding: 10px;margin-right:-180px; border-radius: 5px; margin-bottom: 20px;">
                <div class="form-group col-md-8">
                    <input type="text" id="search" class="form-control" placeholder="Enter Booking ID" onkeyup="searchAndFilter()">
                </div>
                <div class="col-md-4">
                    <select class="form-control" id="statusFilter">
                        <option value="">All</option>
                        <option value="Pending">Booked</option>
                        <option value="Approved">Ongoing</option>
                        <option value="Denied">Completed</option>
                    </select>
                </div>
            </div>
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
            <h4 class="card-title">Rentals</h4>
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
                            <strong>Driver Assigned</strong>
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
                                    <td>{{ $rent->driver->firstName }} {{ $rent->driver->lastName }}</td>
                                    <td style="color: {{ $rent->rent_Period_Status === 'In Progress' ? 'orange' : ($rent->rent_Period_Status === 'Booked' ? 'blue' : ($rent->rent_Period_Status === 'Completed' ? 'green' : 'black')) }}">
                                        <strong>{{ $rent->rent_Period_Status }} </strong>
                                    </td>                                    
                                    <td>{{ $rent->payment_Status }}</td>
                                    <td>{{ $rent->extra_Hours !== null ? $rent->extra_Hours : '--none--' }}</td>
                                    <td>{{ $rent->total_Price }}</td>
                                    <td>{{ $rent->balance }}</td>

                                   
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
        /*
        $(document).ready(function () {
            $('#search').on('keyup', function () {
                var searchText = $(this).val().toLowerCase();

                // Loop through each row in the table
                $('#table tbody tr').each(function () {
                    var bookingId = $(this).find('td:eq(1)').text().toLowerCase();

                    if (bookingId.includes(searchText)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
        */

        /*
        $(document).ready(function () {
        // Function to filter the table rows based on the location query
        function searchByLocation(query) {
            var table = $('#table');
            table.find('tbody tr').each(function () {
                var row = $(this);
                var locationCell = row.find('td:nth-child(2)'); // Adjust the column index if needed
                var location = locationCell.text().trim();
                var found = false;

                // Check if the location contains the query
                if (location.toLowerCase().includes(query.toLowerCase())) {
                    found = true;
                }

                if (found) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        }

        // Handle search input keyup
        $('#search').on('input', function () {
            var query = $(this).val();
            searchByLocation(query);
        });
    });
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
