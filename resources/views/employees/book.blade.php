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
                    <select class="form-control" id="statusFilter" onchange="searchAndFilter()">
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
            <h4 class="card-title">Booking List</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table" class="table table-hover table-striped" style="font-size: 12px;">
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

                        @if ($bookings !== null && $bookings->count() > 0)
                        @php
                            // Sort the $bookings array so that 'Pending' statuses come first
                            $bookings = $bookings->sortBy(function ($booking) {
                                return $booking->status === 'Pending' ? 0 : 1;
                            });
                        @endphp
                            @foreach ($bookings as $booking)
                            <tr class="{{ $booking->status === 'Approved' ? 'table-success' : ($booking->status === 'Denied' ? 'table-danger' : '') }}">
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $booking->customer->firstName }}</td>
                                    <td>{{ $booking->vehicle->unitName }} - {{ $booking->vehicle->registrationNumber }}</td>
                                    <td>{{ $booking->tariff->location }}</td>
                                    <td>{{ \Carbon\Carbon::parse($booking->startDate)->format('l M d, Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($booking->endDate)->format('l M d, Y') }}</td>
                                    <td>{{ $booking->mobileNum }}</td>
                                    <td>{{ $booking->pickUp_Address }}</td>
                                    <td>{{ $booking->note }}</td>
                                    <td>{{ $booking->gcash_RefNum }}</td>
                                    <td>{{ $booking->downpayment_Fee }}</td>
                                    <td>{{ $booking->subtotal }}</td>
                                    <td>{{ $booking->status }}</td>
                                    <td class="col-md-1">
                                        @if ($booking->status === 'Pending') <!-- Only show actions if the status is "Pending" -->
                                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#approveModal{{ $booking->reserveID }}">
                                                <strong>Approve</strong>
                                            </button>
                                        
                                        <!-- Approval Modal -->
                                        <div class="modal fade" id="approveModal{{ $booking->reserveID }}" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel{{ $booking->reserveID }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg"  role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="background-color:#175097;">
                                                        <h5 class="modal-title" id="approveModalLabel{{ $booking->reserveID }}" style="color: white">Confirm Approval</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form method="POST" action="{{ route('employee.approveBooking', $booking->reserveID) }}">
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
                                                                <select class="form-control" id="paymentStatus" name="paymentStatus">
                                                                    <option value="Pending">Pending</option>
                                                                    <option value="Paid">Paid</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="totalPrice">Total Price: (Php)</label>
                                                                <input type="text" style="background-color: rgba(111, 198, 111, 0.544);color:rgb(40, 38, 38);" class="form-control" id="totalPrice" name="totalPrice" value="   {{ $booking->subtotal + $booking->downpayment_Fee }}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="balance">Balance: (Php)</label>
                                                                <input type="text" style="background-color: rgba(111, 198, 111, 0.544);color:rgb(40, 38, 38);" class="form-control" id="balance" name="balance" value="    {{ $booking->subtotal - $booking->downpayment_Fee }}" readonly>
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
                            <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#denyModal{{ $booking->reserveID }}">
                                <strong>Deny</strong>
                            </button>
                            
                            <!-- Deny Modal -->
                            <div class="modal fade" id="denyModal{{ $booking->reserveID }}" tabindex="-1" role="dialog" aria-labelledby="denyModalLabel{{ $booking->reserveID }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="denyModalLabel{{ $booking->reserveID }}">Confirm Denial</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="POST" action="{{ route('employee.denyBooking', $booking->reserveID) }}">
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
                            @endif                
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="12">No bookings made.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    

    
</div>
@endsection

<script>
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
</script>


