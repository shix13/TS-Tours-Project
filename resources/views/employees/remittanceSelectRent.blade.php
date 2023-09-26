@extends('layouts.empbar')

@section('title')
    TS | Remittance
@endsection

@section('content')
<br><br>

<div class="container">
    <div class="row">
        <div class="col-md-1">
            <a href="{{ route('employee.remittance') }}" class="btn btn-danger">BACK</a>
        </div>
        <div class="col-md-9">
            <div class="form-row" style="background-color: hsla(0, 0%, 100%, 0.7); padding: 10px;margin-left:10px; margin-right:-180px; border-radius: 5px; margin-bottom: 20px;">
                <div class="form-group col-md">
                    <input type="text" id="search" class="form-control" placeholder="Enter Rent ID, Booking ID, Driver Assigned, etc." onkeyup="searchAndFilter()">
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
                                    <td style="color: {{ $rent->rent_Period_Status === 'In Progress' ? 'orange' : ($rent->rent_Period_Status === 'Booked' ? 'blue' : ($rent->rent_Period_Status === 'Completed' ? 'green' : 'black')) }}">
                                        <strong>{{ $rent->rent_Period_Status }} </strong>
                                    </td>                                    
                                    <td>{{ $rent->payment_Status }}</td>
                                    <td>{{ $rent->extra_Hours !== null ? $rent->extra_Hours : '--none--' }}</td>
                                    <td>{{ $rent->total_Price }}</td>
                                    <td>{{ $rent->balance }}</td>

                                   
                                    <td class="col-md-1">
                                        <a href="{{ route('remittance.create', ['id' => $rent->rentID]) }}" class="btn btn-success btn-block" style="margin-bottom: 10px;"><strong>Select</strong></a>
                                    </td>                                   
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="12">No rentals with pending payment.</td>
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
    </script>
@endsection
