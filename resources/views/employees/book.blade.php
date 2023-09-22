@extends('layouts.empbar')

@section('title')
    TS | Booking & Rent
@endsection

@section('content')
<br><br>

<div class="container">

    <div class="row">
        <div class="col-md-0">
            <a href="{{ route('employee.rental') }}" class="btn btn-danger" style="padding:25px 30px 25px 30px;margin-left:15px;margin-top:0%"><strong>View Rentals</strong></a>
        </div>
        <div class="col-md-8">
            <div class="form-row" style="background-color: hsla(0, 0%, 100%, 0.7); padding: 10px;margin-right:-180px; border-radius: 5px; margin-bottom: 20px;">
                <div class="form-group col-md-8">
                    <input type="text" id="search" class="form-control" placeholder="Search booking" onkeyup="searchAndFilter()">
                </div>
                <div class="col-md-4">
                    <select class="form-control" id="statusFilter">
                        <option value="">All</option>
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Denied">Denied</option>
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
        
    <div class="card " style="left:-50px;width: 110%;">
        <div class="card-header">
            <h4 class="card-title">Pending Bookings</h4>
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
                            <strong>Fleet</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Destination</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Pick-up Schedule</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Drop-Off Schedule</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Contact Number</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Pick-up Address</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Note</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Gcash Reference No.</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Amount Paid</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Subtotal</strong>
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
                                    <td>{{ $pendingBooking->customer->firstName }} {{ $pendingBooking->customer->lastName }}</td>
                                    <td>{{ $pendingBooking->vehicle->unitName }} - {{ $pendingBooking->vehicle->registrationNumber }}</td>
                                    <td>{{ $pendingBooking->tariff->location }}</td>
                                    <td>{!! \Carbon\Carbon::parse($pendingBooking->startDate)->format(' M d, Y <br>H:i A') !!}</td>
                                    <td>{!! \Carbon\Carbon::parse($pendingBooking->endDate)->format('M d, Y <br>H:i A') !!}</td>
                                    <td>{{ $pendingBooking->mobileNum }}</td>
                                    <td>{{ $pendingBooking->pickUp_Address }}</td>
                                    <td>{{ $pendingBooking->note }}</td>
                                    <td>{{ $pendingBooking->gcash_RefNum }}</td>
                                    <td>{{ $pendingBooking->downpayment_Fee }}</td>
                                    <td>{{ $pendingBooking->subtotal }}</td>
                                    <td>{{ $pendingBooking->status }}</td>
                                    <td class="col-md-1">
                                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#approveModal{{ $pendingBooking->reserveID }}">
                                                <strong>Approve</strong>
                                            </button>
                                        
                                        <!-- Approval Modal -->
                                        <div class="modal fade" id="approveModal{{ $pendingBooking->reserveID }}" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel{{ $pendingBooking->reserveID }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg"  role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="background-color:#175097;">
                                                        <h5 class="modal-title" id="approveModalLabel{{ $pendingBooking->reserveID }}" style="color: white">Confirm Approval</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form method="POST" action="{{ route('employee.approveBooking', $pendingBooking->reserveID) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body " style="font-size: 15px;">
                                                            <div class="form-group">
                                                                <label for="driverID">Driver ID:</label>
                                                                <select class="form-control" id="driverID" name="driverID">
                                                                    @foreach($drivers as $driver)
                                                                        <option value="{{ $driver->empID }}">{{ $driver->firstName }} {{ $driver->lastName }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>                                                            
                                                            <div class="form-group">
                                                                <label for="extraHours">Extra Hours:</label>
                                                                <input type="text" class="form-control" id="extraHours" name="extraHours">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="paymentStatus">Full Payment Status:</label>
                                                                <input type="text" style="background-color:white; color: rgb(40, 38, 38);" class="form-control" id="paymentStatus" name="paymentStatus" value="{{ $pendingBooking->subtotal - $pendingBooking->downpayment_Fee !== 0 ? 'Pending' : 'Paid' }}" readonly>
                                                            </div>                                                            
                                                            <div class="form-group">
                                                                <label for="totalPrice">Total Price: (Php)</label>
                                                                <input type="text" style="background-color:white;color:rgb(40, 38, 38);" class="form-control" id="totalPrice" name="totalPrice" value="   {{ $pendingBooking->subtotal}}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="balance">Balance: (Php)</label>
                                                                <input type="text" style="background-color: rgba(111, 198, 111, 0.544);color:rgb(40, 38, 38);" class="form-control" id="balance" name="balance" value="    {{ $pendingBooking->subtotal - $pendingBooking->downpayment_Fee }}" readonly>
                                                            </div>
                                                            <input type="hidden" name="rentPeriodStatus" value="pending">
                                                            <input type="hidden" name="approvedBy" value="">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-right: 5px;">Cancel</button>
                                                            <!-- Add a submit button to trigger the approval process -->
                                                            <button type="submit" class="btn btn-primary">Approve</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        
                            <br> <br>
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

    <div class="card " style="left:-50px;width: 110%;">
        <div class="card-header">
            <h4 class="card-title">Booking History</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="completedCancelledTable" class="table table-hover table-striped" style="font-size: 12px;">
                    <thead class="text-primary font-montserrat">
                        <th class="bold-text">
                            <strong>#</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Customer Name</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Fleet</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Destination</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Pick-up Schedule</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Drop-Off Schedule</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Contact Number</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Pick-up Address</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Note</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Gcash Reference No.</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Amount Paid</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Subtotal</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Status</strong>
                        </th>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1; // Initialize a counter variable
                        @endphp

                        @if ($completedBookings !== null && $completedBookings->count() > 0)
                            @foreach ($completedBookings as $completedBooking)
                            @if ($completedBooking->status === 'Approved' || $completedBooking->status === 'Denied')
                            <tr class="{{ $completedBooking->status === 'Approved' ? 'table-success' : ($completedBooking->status === 'Denied' ? 'table-danger' : '') }} text-center">
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $completedBooking->customer->firstName }} {{ $completedBooking->customer->lastName }}</td>
                                    <td>{{ $completedBooking->vehicle->unitName }} - {{ $completedBooking->vehicle->registrationNumber }}</td>
                                    <td>{{ $completedBooking->tariff->location }}</td>
                                    <td>{!! \Carbon\Carbon::parse($completedBooking->startDate)->format(' M d, Y <br>H:i A') !!}</td>
                                    <td>{!! \Carbon\Carbon::parse($completedBooking->endDate)->format('M d, Y <br>H:i A') !!}</td>
                                    <td>{{ $completedBooking->mobileNum }}</td>
                                    <td>{{ $completedBooking->pickUp_Address }}</td>
                                    <td>{{ $completedBooking->note }}</td>
                                    <td>{{ $completedBooking->gcash_RefNum }}</td>
                                    <td>{{ $completedBooking->downpayment_Fee }}</td>
                                    <td>{{ $completedBooking->subtotal }}</td>
                                    <td>{{ $completedBooking->status }}</td>
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
                {{ $completedBookings->links() }}
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
