@extends('layouts.empbar')

@section('title')
    TS | Remittance
@endsection

@section('content')
<br><br>

<div class="container">

    <div class="row">
        <div class="col-md-2">
            <a href="{{ route('remittance.select-rent') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> New Remittance
            </a>
            
        </div>
        <div class="col-md-8">
            <div class="form-row" style="background-color: hsla(0, 0%, 100%, 0.7); padding: 10px;margin-right:-180px; border-radius: 5px; margin-bottom: 20px;">
                <div class="form-group col-md">
                    <input type="text" id="search" class="form-control" placeholder="Search by Clerk, Driver, Date, etc." onkeyup="searchAndFilter()">
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
        
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Remittance List</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table" class="table table-hover table-striped" style="font-size: 12px;">
                    <thead class="text-primary font-montserrat">
                        <th class="bold-text">
                            <strong>#</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Received by</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Sent by</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Tracking ID</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Receipt Number</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Amount</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Remittance Date</strong>
                        </th>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1; // Initialize a counter variable
                        @endphp

                        @if ($remittance !== null && $remittance->count() > 0)
                            @foreach ($remittance as $r)
                            <tr class="text-center">
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $r->clerk->firstName }} {{ $r->clerk->lastName }}</td>
                                    <td>{{ $r->driver->firstName }} {{ $r->driver->lastName }}</td>
                                    <td>{{ $r->rent->reserveID }}</td>
                                    <td>{{ $r->receiptNum }}</td>
                                    <td>{{ $r->amount }}</td>
                                    <td>{{ $r->created_at->format('M d, Y g:ia') }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="12">No Remittance made.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                {{ $remittance -> links() }}
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
