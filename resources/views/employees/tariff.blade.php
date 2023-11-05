@extends('layouts.empbar')

@section('title')
    TS | Tariffs
@endsection

@section('content')
<br>
<br>
<div class="container">

    <div class="row">
        <div class="col-md-2">
            <a href="{{ route('tariffs.create') }}" class="btn btn-success" style="padding: 15px 25px"> <i class="fas fa-plus"></i> Create Tariff</a>
        </div>
        
        <div class="col-md-8">
            <div class="form-row" style="background-color: hsla(0, 0%, 100%, 0.7); padding: 10px;margin-right:-180px; border-radius: 5px; margin-bottom: 20px;">
                <div class="form-group col-md-12">
                    <input type="text" id="locationSearch" class="form-control" placeholder="Enter location">
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
            <h4 class="card-title">Tariffs</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table" class="table table-hover table-striped">
                    <thead class="text-primary font-montserrat">
                        <th class="bold-text">
                            <strong>#</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Location</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Rate/Day</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Hrs/Rent</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Rate/Extra-Hour</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Pick-Up/Drop-Off Rate</strong>
                        </th>
                        
                        <th class="bold-text">
                            <strong>Note</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Actions</strong>
                        </th>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1; // Initialize a counter variable
                        @endphp
                    
                        @if ($tariffs !== null && $tariffs->count() > 0)
                            @foreach ($tariffs as $tariff)
                                <tr>
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $tariff->location }}</td>
                                    <td>
                                        @if($tariff->rate_Per_Day > 0)
                                            ₱{{ $tariff->rate_Per_Day }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($tariff->rentPerDayHrs > 0)
                                            {{ $tariff->rentPerDayHrs }} Hrs
                                        @endif
                                    </td>
                                    <td>
                                        @if($tariff->rent_Per_Hour > 0)
                                            ₱{{ $tariff->rent_Per_Hour }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($tariff->do_pu > 0)
                                            ₱{{ $tariff->do_pu }}
                                        @endif
                                    </td>
                                    
                                    
                                    
                                    <td>{{ $tariff->note }}</td>
                                    <td class="text-center col-3">
                                        <a href="{{ route('tariff.edit', $tariff->tariffID) }}" class="btn btn-primary col-5"><strong>EDIT</strong></a>
                                        
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal{{ $tariff->tariffID }}"><strong>DELETE</strong></button>
                                        @include('employees.tariffModal', ['tariffID' => $tariff->tariffID])
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8">No tariffs available.</td>
                            </tr>
                        @endif
                    </tbody>
                    
                </table>
                {{ $tariffs->links() }}
            </div>
        </div>
    </div>


    

    
</div>

@endsection

@section('scripts')
<script>
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
        $('#locationSearch').on('input', function () {
            var query = $(this).val();
            searchByLocation(query);
        });
    });
</script>

@endsection
