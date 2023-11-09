@extends('layouts.empbar')

@section('title', 'Create Maintenance Record')

@section('content')
<br><br>
<div class="container">
    <div class="row">
        <div class="col-md-12 offset-md-0">
            <div class="card" >
                <div class="card-header">
                    <h4 class="card-title" style="color: red; "><i class="fas fa-tools"></i> Schedule Maintenance</h4>
                </div>
                <hr>
                <div class="card-body">
                    <form method="POST" action="{{ route('maintenance.store') }}">
                        @csrf

                        <div class="form-row" style="justify-content: center">
                            <!-- Vehicle Field -->
                            <div class="form-group col-md-4">
                                <label for="unitID" style="color:black"><i class="fas fa-car"></i> Vehicle (Model & Registration Number)</label>
                                <select class="form-control{{ $errors->has('unitID') ? ' is-invalid' : '' }}" id="unitID" name="unitID" style="font-size:18px">
                                    <option value="" selected>--Select Vehicle--</option>
                                    @foreach ($vehicles as $vehicle)
                                        <option value="{{ $vehicle->unitID }}">{{ $vehicle->unitName }} - {{ $vehicle->registrationNumber }}</option>
                                    @endforeach
                                </select>
                                <!-- Error message -->
                                @error('unitID')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Mechanic Assigned Field -->
                            <div class="form-group col-md-4">
                                <label for="mechanicAssigned" style="color:black"><i class="fas fa-wrench"></i> Mechanic Assigned:</label>
                                <select class="form-control{{ $errors->has('mechanicAssigned') ? ' is-invalid' : '' }}" id="mechanicAssigned" name="mechanicAssigned" style="font-size:18px">
                                        <option value="" selected>--Select Mechanic--</option>
                                        @foreach ($mechanicEmployees as $employee)
                                            <option value="{{ $employee->empID }}">{{ $employee->firstName }} {{ $employee->lastName }} - ({{ $employee->accountType }})</option>
                                        @endforeach
                                </select>                              
                                <!-- Error message -->
                                @error('mechanicAssigned')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Scheduled Date and Time Field -->
                            <div class="form-group col-md-3">
                                <label for="scheduleDateTime" style="color:black"><i class="fas fa-clock"></i> Scheduled Date and Time:</label>
                                <input type="text" class="form-control" id="scheduleDateTime" name="scheduleDateTime" placeholder="Select Date and Time" style="background: white;  cursor: default;font-size:18px" required>
                                <!-- Error message -->
                                @error('scheduleDateTime')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>                          
                        </div>

                        <hr>
                        <!-- Mileage Field -->
                        <div class="form-group">
                            <label for="mileage" style="color:black"><i class="fas fa-tachometer-alt" ></i> Mileage:</label>
                            <input type="number" min="1" class="form-control{{ $errors->has('mileage') ? ' is-invalid' : '' }}" id="mileage" name="mileage" style="font-size:25px;width:30%">
                            <!-- Error message -->
                            @error('mileage')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        <!-- Notes Field -->
                        <div class="form-group">
                            <label for="notes" style="color:black"><i class="fas fa-sticky-note" ></i> Notes:</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" style="font-size:15px"></textarea>
                        </div>

                        <!-- Scheduled By Field -->
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="empID" style="color:black"><i class="fas fa-user"></i> Scheduled By:</label>
                                <input style="color: black; background: white" type="text" class="form-control" id="scheduledBy" value="{{ auth()->guard('employee')->check() ? auth()->guard('employee')->user()->firstName . ' ' . auth()->guard('employee')->user()->lastName : '' }}" readonly>
                                <input type="hidden" name="empID" id="empID" value="{{ auth()->guard('employee')->check() ? auth()->guard('employee')->user()->empID : '' }}">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $(document).ready(function() {
    var flatpickrInstance; // Declare the Flatpickr instance variable

    // Initialize Flatpickr without any options
    flatpickrInstance = flatpickr('#scheduleDateTime', {});

    // Get the initial selected vehicle ID
    var selectedVehicleId = $('#unitID').val();
    // Get the schedule data for the initial selected vehicle
    updateDateTimeInput(selectedVehicleId);

    $('#unitID').on('change', function() {
        var selectedVehicleId = $(this).val();
        // Get the schedule data for the selected vehicle
        updateDateTimeInput(selectedVehicleId);
    });

    function updateDateTimeInput(selectedVehicleId) {
        var dateTimeInput = $('#scheduleDateTime');

        // If a vehicle is selected, enable Flatpickr and fetch schedules
        if (selectedVehicleId) {
            $.ajax({
                url: '/get-available-schedules/' + selectedVehicleId,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    // Destroy the existing Flatpickr instance
                    flatpickrInstance.destroy();
                    // Re-initialize Flatpickr with new disable dates
                    flatpickrInstance = flatpickr('#scheduleDateTime', {
                        enableTime: true,
                        dateFormat: 'Y-m-d H:i',
                        minDate: 'today',
                        disable: data,
                    });
                    // Enable the input field and change the background color to white
                    dateTimeInput.prop('disabled', false);
                    $('.flatpickr-calendar').css('background-color', 'white');
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Request Error: ' + status + ' - ' + error);
                }
            });
        } else {
            // If no vehicle is selected, keep Flatpickr disabled and disable the input field
            dateTimeInput.prop('disabled', true);
            flatpickrInstance.clear();
        }
    }
});


</script>



@endsection