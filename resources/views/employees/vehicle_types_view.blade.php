@extends('layouts.empbar')

@section('title')
    TS | Create Vehicle Type
@endsection

@section('content')
<br>
<br>
<div class="container">

    <div class="row">
    <div class="col-md-6">
            <a href="{{route('employee.vehicle')}}" class="btn btn-danger" ><i class="fa-solid fa-circle-left"></i> Back</a>
        
            <a href="{{route('vehicleTypes.create')}}" class="btn btn-success"><i class="fas fa-plus"></i> Add New Vehicle Type</a>
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
            <h4 class="card-title">Vehicle Types</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table" class="table table-hover table-striped">
                    <thead class="text-primary font-montserrat">
                        <th class="bold-text">
                            <strong>#</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Vehicle Type</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Actions</strong>
                        </th>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1; // Initialize a counter variable
                        @endphp

                        @if ($vehicleTypes !== null && $vehicleTypes->count() > 0)
                            @foreach ($vehicleTypes as $type)
                                <tr>
                                    <td>{{ $counter++ }}</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-4 text-center">
                                                <div style="width: 200px; height: 150px; overflow: hidden; margin: 0 auto;">
                                                    <img src="{{ asset('storage/' . $type->pic) }}" alt="Vehicle Image" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                                <h5><strong>{{ $type->vehicle_Type }}</strong></h5>
                                            </div>
                                            
                                            <div class="col-md-8">
                                                <p>
                                                    <strong>Description:</strong> {{ $type->description ?? 'None' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center col-3">
                                        <a href="{{ route('vehicleTypes.edit', $type->vehicle_Type_ID) }}" class="btn btn-primary  col-4">EDIT</a>

                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal{{ $type->vehicle_Type_ID }}">
                                            <strong>DELETE</strong>
                                        </button>
                                        <div class="modal fade" id="deleteModal{{ $type->vehicle_Type_ID }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel">Delete Vehicle Type</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to delete this vehicle type?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <form method="POST" action="{{ route('vehicleTypes.destroy', $type->vehicle_Type_ID) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </td>
                                    
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3">No vehicle types available.</td>
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
@endsection
