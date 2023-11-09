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
                        <a class="nav-link {{ Route::is('employee.booking') ? 'active' : '' }}" href="{{ route('employee.booking') }}">Bookings</a>
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
                <div class="row">
                    <div class="col">
                        <div class="btn-group" role="group" aria-label="Button Group">
                            <a href="{{ route('employee.preapproved') }}" class="btn btn-info">Pre-approved Booking</a>
                            <a href="{{ route('employee.paymentHistory') }}" class="btn btn-primary">Payment History</a>
                        </div>
                    </div>
                    <div class="col" style="text-align:right;">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#editGcash">Edit GCash QR Code</button>
                    </div>

                    <!-- Modal to edit QR code -->
                    <div class="modal fade" id="editGcash">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Upload a New Image</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="container">
                                        <form id="imageUploadForm" method="POST" action="{{ route('employee.uploadQR') }}" enctype="multipart/form-data">
                                            <div class="form-group">
                                                @csrf
                                                <div class="row">
                                                    <div class="col">
                                                        <h8>Original Image:</h8>
                                                        <img src="{{ asset('storage/images/gcash.jpg') }}" alt="GCash" style="width: 100%; margin-left: 0%">
                                                    </div>
                                                    <div class="col">
                                                        <h8>Updated Image:</h8>
                                                        <img id="picPreview" src="#" alt="Selected Image" style="display: none; max-width: 100%; max-height: 100%;">
                                                        <label for="newImage" class="custom-file-upload" style="color: black">
                                                            <i class="fas fa-upload"></i> Select Photo
                                                        </label>
                                                        <input type="file" name="newImage" id="newImage" accept="image/*" onchange="displayImage(this)">
                                                    </div>
                                                </div>
                                                <div class="row text-center">
                                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Image</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="scheduledInProgressTable" class="table table-hover table-striped">
                        <thead class="text-primary font-montserrat">
                            <tr>
                                <th class="bold-text"><strong>#</strong></th>
                                <th class="bold-text"><strong>Booking No.</strong></th>
                                <th class="bold-text"><strong>Total Amount</strong></th>
                                <th class="bold-text"><strong>Downpayment Paid</strong></th>
                                <th class="bold-text"><strong>Gcash Reference No.</strong></th>
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
                                    <tr class="text-center" data-toggle="popover" data-trigger="hover" title="Additional Details" data-content="<strong>{{$preApprovedBooking->reserveID}}</strong> <br> Customer Name: {{$preApprovedBooking->cust_first_name}} {{$preApprovedBooking->cust_last_name}} <br> Contact Number: {{$preApprovedBooking->mobileNum}} <br> Email: {{$preApprovedBooking->cust_email}}">
                                        <td>{{ $counter++ }}</td>
                                        <td><strong>{{ $preApprovedBooking->reserveID }}</strong></td>
                                        <td>₱ {{ $preApprovedBooking->subtotal }}</td>
                                        <td>₱ {{ $preApprovedBooking->downpayment_Fee }}</td>
                                        <td>{{ $preApprovedBooking->gcash_RefNum }}</td>
                                        <td>{{ $preApprovedBooking->status }}</td>
                                        <td class="col-md-1">
                                            <a href="{{ route('employee.approveBooking', ['bookingId' => $preApprovedBooking->reserveID]) }}" class="btn btn-success btn-block approve-btn" style="margin-bottom: 10px;"><strong><i class="fa-solid fa-check"></i> Approve</strong></a>

                                            <!-- Trigger the modal for denying the booking -->
                                            <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#denyModal{{ $preApprovedBooking->reserveID }}"><strong><i class="fa-solid fa-xmark"></i> Deny</strong></button>
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
</div>
@endsection

@section('scripts')
<script>
    // Wait for the document to be ready
    $(document).ready(function() {
        // Initialize Bootstrap popover
        $('[data-toggle="popover"]').popover({
            html: true,
            content: function () {
                // Get the content from the hidden div and populate it dynamically
                var content = $($(this).data('content')).html();
                var row = $(this).closest('tr');

                // Populate details
                content = content.replace('#bookingNo', row.find('td:eq(1)').text().trim());
                content = content.replace('#totalAmount', row.find('td:eq(2)').text().trim());
                content = content.replace('#downpaymentPaid', row.find('td:eq(3)').text().trim());
                content = content.replace('#gcashRefNo', row.find('td:eq(4)').text().trim());

                // Add more details as needed

                return content;
            }
        });

        // Add click event listener to the "Approve" button
        $('.approve-btn').on('click', function(event) {
            // Get the value of gcash_RefNum from the corresponding table cell
            var gcashRefNum = $(this).closest('tr').find('td:eq(4)').text().trim();

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
            var gcashRefNum = $(this).closest('tr').find('td:eq(4)').text().trim();

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
    });
</script>
<script>
    function displayImage(input) {
        const picPreview = document.getElementById('picPreview');
        console.log('Function called'); // Debugging: Check if the function is called
        if (input.files && input.files[0]) {
            console.log('File selected:', input.files[0].name); // Debugging: Check the selected file
            const reader = new FileReader();
            reader.onload = function(e) {
                picPreview.src = e.target.result;
                picPreview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            console.log('No file selected'); // Debugging: Check if no file is selected
            picPreview.style.display = 'none';
        }
    }
    // Update the image
    function updateImage() {
        const newImageInput = document.getElementById('newImage');
        const image = document.querySelector('img');
        const file = newImageInput.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                image.src = e.target.result;
                closeModal();
            };

            reader.readAsDataURL(file);
        }
    }
</script>
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
    /*
    function refreshPage() {
            location.reload(); // Reload the current page
        }

        // Call the refreshPage function every X milliseconds (e.g., every 5 seconds)
        setTimeout(refreshPage, 30000); 
        */
</script>

@endsection
