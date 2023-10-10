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
        
    <div class="card">
        <div class="card-header">
            <h4 class="card-title" style="font-weight: 700">Pre-Approved Booking</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="scheduledInProgressTable" class="table table-hover table-striped">
                    <thead class="text-primary font-montserrat">
                        <tr>
                            <th class="bold-text"><strong>#</strong></th>
                            <th class="bold-text"><strong>Booking ID</strong></th>
                            <th class="bold-text"><strong>Downpayment Fee</strong></th>
                            <th class="bold-text"><strong>Gcash Reference No.</strong></th>
                            <th class="bold-text"><strong>Subtotal</strong></th>
                            <th class="bold-text"><strong>Status</strong></th>
                            <th class="bold-text"><strong>Actions</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1; // Initialize a counter variable
                        @endphp

                        @if ($preApprovedBookings !== null && $preApprovedBookings->count() > 0)
                            @foreach ($preApprovedBookings as $preApprovedBooking)
                                <tr class="text-center">
                                    <td>{{ $counter++ }}</td>
                                    <td><strong>{{ $preApprovedBooking->reserveID }}</strong></td>
                                    <td>{{ $preApprovedBooking->downpayment_Fee }}</td>
                                    <td>{{ $preApprovedBooking->gcash_RefNum }}</td>
                                    <td>{{ $preApprovedBooking->subtotal }}</td>
                                    <td>{{ $preApprovedBooking->status }}</td>
                                    <td class="col-md-1">
                                        <a href="{{ route('employee.approveBooking', ['bookingId' => $preApprovedBooking->reserveID]) }}" class="btn btn-success btn-block approve-btn" style="margin-bottom: 10px;"><strong><i class="fa-solid fa-check"></i> Approve</strong></a>
                                      
                                        <!-- Trigger the modal for denying the booking -->
                                        <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#denyModal{{ $preApprovedBooking->reserveID }}"><strong><i class="fa-solid fa-xmark"></i> Reject</strong></button>
                                    </td>
                                </tr>

                                <!-- Deny Modal -->
                                <div class="modal fade" id="denyModal{{ $preApprovedBooking->reserveID }}" tabindex="-1" role="dialog" aria-labelledby="denyModalLabel{{ $preApprovedBooking->reserveID }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="denyModalLabel{{ $preApprovedBooking->reserveID }}">Confirm Denial</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form method="POST" action="{{ route('employee.denyBooking', ['bookingId' => $preApprovedBooking->reserveID]) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    Are you sure you want to deny this booking?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-right: 5px;">Cancel</button>
                                                    <!-- Add a submit button to trigger the denial process -->
                                                    <button type="submit" class="btn btn-danger">Confirm</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="12">No rentals made.</td>
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
    // Wait for the document to be ready
    $(document).ready(function() {
        // Add click event listener to the "Approve" button
        $('.approve-btn').on('click', function(event) {
            // Get the value of gcash_RefNum from the corresponding table cell
            var gcashRefNum = $(this).closest('tr').find('td:eq(3)').text().trim();

            // Check if gcash_RefNum is empty
            if (gcashRefNum === '') {
                // Display a confirmation dialog
                var confirmed = confirm('The Gcash Reference Number is empty. Are you sure you want to approve this booking?');

                // If user cancels the confirmation, prevent the default link behavior
                if (!confirmed) {
                    event.preventDefault();
                }
            }
        });

        // Add click event listener to the "Reject" button within the modal
        $('.reject-btn').on('click', function(event) {
            // Get the value of gcash_RefNum from the corresponding table cell
            var gcashRefNum = $(this).closest('tr').find('td:eq(3)').text().trim();

            // Check if gcash_RefNum is empty
            if (gcashRefNum === '') {
                // Display a confirmation dialog
                var confirmed = confirm('The Gcash Reference Number is empty. Are you sure you want to reject this booking?');

                // If user cancels the confirmation, prevent the modal from being shown
                if (!confirmed) {
                    event.preventDefault();
                }
            }
        });
</script>
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
        $('#search').on('input', function () {
            var query = $(this).val();
            // Check the conditions and show/hide rows accordingly
            if (query === '') {
                $('#scheduledInProgressTable tbody tr').show();
            } else {
                filterAndSearchTable(query);
            }
        });
    });
</script>
@endsection
