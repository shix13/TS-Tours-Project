@extends('layouts.index')

@section('content')
<br><br>

<div class="container">
    <div class="form-group col-md-6">
        <input type="text" id="search" class="form-control" placeholder="Search booking" onkeyup="searchAndFilter()" style="background: white;padding:20px;border:2px solid midnightblue;">
    </div>
     
    <div class="card mx-auto" style="width: 1200px;margin-top: 10px;background: white;border:2px solid midnightblue">
        <div class="card-header">
            <h1 class="card-title" style="text-align: left; padding: 10px;"><strong>Booking Records</strong></h1>
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
                                    <td>{{ $booking->reserveID }}</td>
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
    var table = document.getElementById('table');
    var rows = table.getElementsByTagName('tr');

    // Loop through all table rows, and show/hide them based on the search text
    for (var i = 1; i < rows.length; i++) {
        var row = rows[i];
        var columns = row.getElementsByTagName('td');
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

        if (found) {
            row.style.display = ''; // Show the row if it contains the search text
        } else {
            row.style.display = 'none'; // Hide the row if it doesn't contain the search text
        }
    }
    }

    // Add an event listener to the table row
    document.addEventListener("DOMContentLoaded", function () {
            var table = document.getElementById("table");
            var rows = table.getElementsByTagName("tr");

            for (var i = 0; i < rows.length; i++) {
                var row = rows[i];
                row.addEventListener("click", function () {
                    // Get the data you need from the clicked row
                    var cell = this.cells[0]; // Adjust the index based on your table structure
                    var data = cell.textContent;
                    var cell1= this.cells[12];
                    var status= cell1.textContent;

                    // Construct the URL based on the data or any other logic
                    var url;
        if (status === 'Pending') {
            url = "/bookingstatus" + data; // Redirect to a URL for 'Pending' status
        } else if (status === 'Approved') {
            url = "/approvedbookingstatus" + data; // Redirect to a URL for 'Approved' status
        } else if (status === 'Denied') {
            url = "/deniedbookingstatus" + data; // Redirect to a URL for 'Denied' status
        }
        window.location.href = url;
                });
            }
        });
</script>



