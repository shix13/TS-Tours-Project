@extends('layouts.empbar')

@section('title', 'Create Maintenance Record')

@section('content')
<br><br>
<div class="container">
    <div class="row">
        <div class="col-md-12 offset-md-0">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title" style="color: red;font-weight:700">Schedule Maintenance</h4>
                </div>
                <hr>
                <div class="card-body">
                    <form method="POST" action="{{ route('maintenance.store') }}" >
                        @csrf

                        <div class="form-row" style="justify-content: center">
                            <!-- Unit ID (Foreign Key to Vehicles Table) -->
                            <div class="form-group col-md-4">
                                <label for="unitID">Vehicle (Model & Registration Number)</label>
                                <select class="form-control{{ $errors->has('unitID') ? ' is-invalid' : '' }}" id="unitID" name="unitID">
                                    <!-- Populate this dropdown with options from your vehicles table -->
                                    @foreach ($vehicles as $vehicle)
                                        @php
                                            // Check if the vehicle is active
                                            $isActive = $vehicle->status === 'Active';
                                            // Check if the vehicle is not already scheduled with status 'Scheduled' or 'In Progress'
                                            $isScheduled = $maintenances->where('unitID', $vehicle->unitID)
                                                ->whereIn('status', ['Scheduled', 'In Progress'])
                                                ->isNotEmpty();
                                        @endphp
                                        @if ($isActive && !$isScheduled)
                                            <option value="{{ $vehicle->unitID }}">{{ $vehicle->unitName }} - {{ $vehicle->registrationNumber }}</option>
                                        @endif
                                    @endforeach
                                </select>                                
                                @if ($errors->has('unitID'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>**{{ $errors->first('unitID') }}</strong>
                                    </span>
                                @endif
                            </div>
                            
                            

                            <!-- Mechanic Assigned (Foreign Key to Employees Table) -->
                            <div class="form-group col-md-4">
                                <label for="mechanicAssigned">Mechanic Assigned:</label>
                                <select class="form-control{{ $errors->has('mechanicAssigned') ? ' is-invalid' : '' }}" id="mechanicAssigned" name="mechanicAssigned">
                                    @php
                                        $mechanicFound = false; // Initialize a flag
                                    @endphp

                                    @foreach ($employees as $employee)
                                        @if ($employee->accountType === 'Mechanic')
                                            <option value="{{ $employee->empID }}">{{ $employee->firstName }} {{ $employee->lastName }}</option>
                                            @php
                                                $mechanicFound = true; // Set the flag to true if a mechanic is found
                                            @endphp
                                        @endif
                                    @endforeach

                                    @if (!$mechanicFound)
                                        <option value="" disabled selected>No Mechanic Account Exists in the Database (Ask the Manager to Assign One).</option>
                                    @endif
                                </select>
                                @if ($errors->has('mechanicAssigned'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>**{{ $errors->first('mechanicAssigned') }}</strong>
                                    </span>
                                @endif
                            </div>

                            
                            <div class="form-group col-md-2">
                                <label for="scheduleDateTime">Scheduled Date and Time:</label>
                                <input type="datetime-local" class="form-control{{ $errors->has('scheduleDateTime') ? ' is-invalid' : '' }}" 
                                       id="scheduleDateTime" name="scheduleDateTime" min="{{ date('Y-m-d\TH:i') }}">
                                @if ($errors->has('scheduleDateTime'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>**{{ $errors->first('scheduleDateTime') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <input type="hidden" name="status" value="Scheduled">
                            
                            
                        </div>

                        <div class="form-group">
                            <label for="notes">Notes:</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>

                        <div class="form-row">
                            <!-- Employee ID (Foreign Key to Employees Table) -->
                            <div class="form-group col-md-4">
                                <label for="empID">Scheduled By:</label>
                                <input style="color: black;background:white" type="text" class="form-control" id="scheduledBy" value="{{ auth()->guard('employee')->check() ? auth()->guard('employee')->user()->firstName . ' ' . auth()->guard('employee')->user()->lastName : '' }}" readonly>
                                <input type="hidden" name="empID" id="empID" value="{{ auth()->guard('employee')->check() ? auth()->guard('employee')->user()->empID : '' }}">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
