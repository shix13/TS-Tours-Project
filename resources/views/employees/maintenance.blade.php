@extends('layouts.empbar')

@section('title')
    TS | Maintenace
@endsection

@section('content')
<br>
<br>

<div class="container">
    <div class="row">
        <div class="col-md-2">
            <a href="{{ route('maintenance.history') }}" class="btn btn-info">
                <i class="fas fa-history"></i> Maintenance History
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('maintenance.create') }}" class="btn btn-success" style="padding: 18px 25px">
                <i class="fas fa-plus"></i> Add Schedule
            </a>
        </div>
        
        
        <div class="col-md-6">
            <div class="form-row" style="background-color: hsla(0, 0%, 100%, 0.7); padding: 10px;margin-right:-180px; border-radius: 5px; margin-bottom: 20px;">
                <div class="form-group col-md-8">
                    <input type="text" id="search" class="form-control" placeholder="Search by Fleet, Mechanic, Notes, Scheduled By">
                </div>
                <div class="col-md-4">
                    <select id="statusFilter" class="form-control">
                        <option value="">All</option>
                        <option value="Scheduled">Scheduled</option>
                        <option value="In Progress">In Progress</option>
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

    <!-- Table for Scheduled and In Progress Maintenance Records -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Maintenance Schedule</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="scheduledInProgressTable" class="table table-hover table-striped">
                    <thead class="text-primary font-montserrat">
                        <th class="bold-text">
                            <strong>#</strong>
                        </th>
                        <th class="bold-text col-md-2">
                            <strong>Fleet</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Information</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Status</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Scheduled By</strong>
                        </th>

                        
                        <th class="bold-text">
                            <strong>Action</strong>
                        </th>
                    </thead>
                    <tbody>
                        @php
                        $counter = 1; // Initialize a counter variable
                        @endphp

                        @if ($maintenances !== null && $maintenances->count() > 0)
                        @foreach ($maintenances as $maintenance)
                        
                        <tr>
                            <td>{{ $counter++ }}</td>
                            <td class="text-center">
                                <div style="width: 200px; height: 150px; overflow: hidden; margin: 0 auto;">
                                    <img src="{{ asset('storage/' . $maintenance->vehicle->pic) }}" alt="Vehicle Image" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <span id="unitname"><strong>{{$maintenance->vehicle->unitName}} - {{$maintenance->vehicle->registrationNumber}}</strong></span>
                            </td>
                            <td> 
                               
                               <span id="mechanicAssigned"> <strong>Mechanic: </strong>{{ $maintenance->mechanic_firstName }} {{ $maintenance->mechanic_lastName }} <br> </span>
                                
                                
                               <span id="scheduledate"> <strong>Start Date: </strong>{{ date('M d, Y h:i A', strtotime($maintenance->scheduleDate)) }}</span> <hr>
                                
                            <span class="{{ $maintenance->notes === null ? 'text-center' : '' }}">
                                @if ($maintenance->notes === null)
                                    <div style="text-align: center" id="notes"><strong>--No Notes--</strong></div>
                                @else
                                  <span id="notes"> Note: {!! $maintenance->notes !!}</span>
                                @endif
                            </span>  <hr>
                            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="changeMechanicButton">
                                <i class="fas fa-wrench"></i> Change Mechanic
                            </button>  
                            <div class="dropdown-menu" aria-labelledby="changeMechanicButton">
                                @foreach($mechanics as $mechanic)
                                    <a class="dropdown-item" href="{{ route('maintenance.updateMechanic', ['id' => $maintenance->maintID, 'mechanic_id' => $mechanic->empID]) }}">
                                        {{ $mechanic->firstName }} {{ $mechanic->lastName }} - ({{ $mechanic->accountType }})
                                    </a>
                                @endforeach
                            </div>
                            </td>
                            <td class="status col-md-0 align-middle text-center">
                                <span id="status"
                                    style="color: {{ $maintenance->status === 'Scheduled' ? 'blue' : ($maintenance->status === 'In Progress' ? 'orange' : ($maintenance->status === 'Cancelled' ? 'red' : ($maintenance->status === 'Completed' ? 'green' : ''))) }}">
                                    <strong>{{ $maintenance->status }}</strong>
                                </span>
                            </td>
                            <td id="scheduledby" class="text-center"> {{ $maintenance->scheduled_by_firstName }} {{ $maintenance->scheduled_by_lastName }}</td>
                            <td>
                                <div class="text-center">
                                    <div class="btn-group">
                                        @if($maintenance->status === 'Completed' || $maintenance->status === 'Cancelled')
                                            <button type="button" class="btn btn-primary" disabled>
                                                Update Status
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="updateStatusButton">
                                                Update Status
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="updateStatusButton">
                                                <form id="statusForm" action="{{ route('maintenance.update', ['id' => $maintenance->maintID]) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group">
                                                        <select class="form-control" name="status" id="statusSelect">
                                                            <option value="In Progress" @if($maintenance->status === 'In Progress') selected @endif>In Progress</option>
                                                            <option value="Cancelled" @if($maintenance->status === 'Cancelled') selected @endif>Cancelled</option>
                                                            <option value="Completed" @if($maintenance->status === 'Completed') selected @endif>Completed</option>
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary btn-block">Save</button>
                                                </form>
                                            </div>                                            
                                        @endif
                                    </div>                                                                      
                                </div>
                            </td>
                            
                        </tr>
                        
                        @endforeach
                        @else
                        <tr>
                            <td colspan="12">No maintenance items available.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Table for Completed and Cancelled Maintenance Records -->
   
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
    // Function to filter the table rows based on status and search query
    function filterAndSearchTable(tableId, status, query) {
        var table = $('#' + tableId);
        table.find('tbody tr').each(function () {
            var row = $(this);
            var statusCell = row.find('#status'); // Update to target element by id
            var mechanicCell = row.find('#mechanicAssigned'); // Update to target element by id
            var notesCell = row.find('#notes'); // Update to target element by id
            var unitNameCell = row.find('#unitname'); // Update to target element by id
            var scheduledByCell = row.find('#scheduledby'); // Update to target element by id
            var scheduledDateCell = row.find('#scheduledate'); // Update to target element by id

            var rowStatus = statusCell.text().trim().toLowerCase();
            var rowMechanic = mechanicCell.text().trim().toLowerCase();
            var rowNotes = notesCell.text().trim().toLowerCase();
            var rowUnitName = unitNameCell.text().trim().toLowerCase();
            var rowScheduledBy = scheduledByCell.text().trim().toLowerCase();
            var rowScheduledDate = scheduledDateCell.text().trim().toLowerCase();

            var found = false;

            // Condition 1: Status is null, mechanic name is null, notes is null, unit name is null, scheduled by is null, scheduled date is null
            if (status === '' && query === '' && rowMechanic === '' && rowNotes === '' && rowUnitName === '' && rowScheduledBy === '' && rowScheduledDate === '') {
                found = true;
            }
            // Condition 2: Check if any of the values match the search query
            else {
                if (
                    (status === '' || rowStatus === status.toLowerCase()) &&
                    (query === '' ||
                        rowMechanic.includes(query.toLowerCase()) ||
                        rowNotes.includes(query.toLowerCase()) ||
                        rowUnitName.includes(query.toLowerCase()) ||
                        rowScheduledBy.includes(query.toLowerCase()) ||
                        rowScheduledDate.includes(query.toLowerCase())
                    )
                ) {
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

    // Handle filter button change and search input keyup/change
    $('#statusFilter, #search').on('input', function () {
        var status = $('#statusFilter').val();
        var query = $('#search').val();

        // Check the conditions and show/hide tables and rows accordingly
        if (status === '' && query === '') {
            $('#scheduledInProgressTable, #completedCancelledTable').show();
            $('#scheduledInProgressTable tbody tr, #completedCancelledTable tbody tr').show();
        } else if (status === '' && query !== '') {
            $('#scheduledInProgressTable, #completedCancelledTable').show();
            filterAndSearchTable('scheduledInProgressTable', status, query);
            filterAndSearchTable('completedCancelledTable', status, query);
        } else if (status !== '' && query === '') {
            $('#scheduledInProgressTable, #completedCancelledTable').hide();
            filterAndSearchTable('scheduledInProgressTable', status, query);
            filterAndSearchTable('completedCancelledTable', status, query);
            $('#' + (status === 'completed' || status === 'cancelled' ? 'completedCancelledTable' : 'scheduledInProgressTable')).show();
        } else {
            $('#scheduledInProgressTable, #completedCancelledTable').hide();
            filterAndSearchTable('scheduledInProgressTable', status, query);
            filterAndSearchTable('completedCancelledTable', status, query);
            $('#' + (status === 'completed' || status === 'cancelled' ? 'completedCancelledTable' : 'scheduledInProgressTable')).show();
        }
    });
});

$(document).on('click', '#statusSelect', function (e) {
    e.stopPropagation();
});


</script>
@endsection


