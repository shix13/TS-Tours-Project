@extends('layouts.empbar')

@section('title')
    TS | Rental View
@endsection

@section('content')
<br><br>
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
<button type="button" class="btn btn-danger" onclick="goBack()"><i class="fa-solid fa-circle-left"></i> Back</button>
<div class="content">
    <div class="row">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">Rent Information</h5> <hr><span style="background-color: orange; border-radius:5px;padding:5px">Rent Number:<strong> {{$rental->rentID}}</strong> | Booking ID : <strong> {{$bookings[0]->reserveID}} </strong></span>
                </div>

                <div class="card-body" style="font-size: 15px;">
                    <form method="POST" action="{{ route('rental.update', $rental->rentID) }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label style="color: black;"><i class="fa-solid fa-user"></i> Customer Name</label>
                                    <input style="color: black;background-color: rgb(255, 255, 255)" type="text" class="form-control" value='{{ $bookings[0]->cust_first_name }} {{ $bookings[0]->cust_last_name }}' readonly>
                                </div>
                            </div>
                            <div class="col-md-4 pl-1">
                                <div class="form-group">
                                    <label style="color: black;"><i class="fa-solid fa-phone"></i> Contact Number</label>
                                    <input type="text" style="color: black;background-color: rgb(255, 255, 255)" class="form-control" value="{{$bookings[0]->mobileNum}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            

                            <div class="col-md-9 pr-1">
                                <div class="form-group">
                                    <label style="color: black;"><i class="fa-solid fa-location-dot"></i> Travel Location</label>
                                    <input style="color: black; background-color: white" type="text" class="form-control" name="tariff_id" value="{{ $bookings[0]->tariff->location }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-3 pl-1">
                                <div class="form-group">
                                    <label style="color: black;"><i class="fa-solid fa-location-dot"></i> Rate: {{ $bookings[0]->bookingType }} </label>
                                    <input style="color: black; background-color: white" type="text" class="form-control" name="tariff_id" 
                                        @if($bookings[0]->bookingType === 'Rent')
                                        value="{{ $bookings[0]->tariff->rate_Per_Day }}"
                                        
                                        @else
                                        value="{{ $bookings[0]->tariff->do_pu }}"
                                        @endif
                                        readonly>
                                </div>
                            </div>

                           
                        </div>

                        <div class="row">
                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label style="color: black;"><i class="fa-solid fa-calendar-days"></i> Schedule Date</label>
                                    <input style="color: black;background-color: white" type="date" class="form-control" name="pickup_date" value="{{ \Carbon\Carbon::parse($bookings[0]->startDate)->format('Y-m-d') }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-3 pl-1">
                                <div class="form-group">
                                    <label style="color: black;"><i class="fa-regular fa-clock"></i> Pick-Up Time</label>
                                    <input style="color: black;background-color: white" type="time" class="form-control" name="pickup_time" value="{{ \Carbon\Carbon::parse($bookings[0]->startDate)->format('H:i') }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label style="color: black;"><i class="fa-solid fa-calendar"></i> Drop-Off Date</label>
                                    <input style="color: black;background-color: white" type="date" class="form-control" name="dropoff_date" value="{{ \Carbon\Carbon::parse($bookings[0]->endDate)->format('Y-m-d') }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-3 pl-1">
                                <div class="form-group">
                                    <label style="color: black;"><i class="fa-solid fa-clock"></i> Drop-Off Time</label>
                                    <input style="color: black;background-color: white" type="time" class="form-control" name="dropoff_time" value="{{ \Carbon\Carbon::parse($bookings[0]->endDate)->format('H:i') }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="form-group">
                                    <label style="color: black;"><i class="fa-solid fa-map-pin"></i> Pick-Up Address</label>
                                    <input type="text" class="form-control" name="pickup_address" value="{{$bookings[0]->pickUp_Address}}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label style="color: black;"><i class="fa-solid fa-note-sticky"></i> Note</label>
                                    <textarea class="form-control" rows="4" name='note' value="{{$bookings[0]->note}}"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label style="color: black;">
                                        <i class="fas fa-cogs"></i> Booking Status
                                    </label>
                                    <input style="color: black;background-color: rgb(255, 255, 255)" type="text" class="form-control" id="status" name="status" value="{{ $bookings[0]->status }}" readonly>
                                </div>
                            </div>                            
                        
                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label style="color: black;">
                                        <i class="fas fa-receipt"></i> Gcash Reference No.
                                    </label>
                                    <input style="color: black;background-color: rgb(255, 255, 255)" type="text" class="form-control" value="{{$bookings[0]->gcash_RefNum}}" readonly>
                                </div>
                            </div>
                        
                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label style="color: black;">
                                        <i class="fas fa-clock"></i> Rate / Additional Hour
                                    </label>
                                    <input style="color: black; background-color: white" type="text" class="form-control" id="rent_per_hour" value="₱ {{ $bookings[0]->tariff->rent_Per_Hour }}" readonly>
                                </div>
                            </div>
                        
                            <div class="col-md-3 pl-1">
                                <div class="form-group">
                                    <label style="color: black;">
                                        <i class="fa-solid fa-money-bill-1"></i></i> Subtotal (Fleet Only)
                                    </label>
                                    <input style="color: black;background-color: white" type="text" class="form-control" readonly value="₱ {{$bookings[0]->subtotal}}">
                                </div>
                            </div>
                        </div>
                        

                        <div class="row">
                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label style="color: black;">
                                        <i class="fas fa-calendar-alt"></i> Rental Status
                                    </label>
                                    <select class="form-control" name="rental_status" required>
                                        <option value="Scheduled" {{ $rents[0]->rent_Period_Status === 'Scheduled' ? 'selected' : '' }}>
                                             Scheduled
                                        </option>
                                        <option value="Ongoing" {{ $rents[0]->rent_Period_Status === 'Ongoing' ? 'selected' : '' }}>
                                           Ongoing
                                        </option>
                                        <option value="" {{ $rents[0]->rent_Period_Status === 'Completed' ? 'selected' : '' }}>
                                             Completed
                                        </option>
                                        <option value="Cancelled" {{ $rents[0]->rent_Period_Status === 'Cancelled' ? 'selected' : '' }}>
                                             Cancelled
                                        </option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label style="color: black;">
                                        <i class="fa-solid fa-money-bill-transfer"></i> Downpayment Amount
                                    </label>
                                    <input style="color: black;background-color: white" type="text" class="form-control" value="₱ {{$bookings[0]->downpayment_Fee}}" readonly>
                                </div>
                            </div>

                           
                        
                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label style="color: black;">
                                        <i class="fa-solid fa-hourglass-half"></i> Total Hours
                                    </label>
                                    <input style="color: black;background-color: rgb(255, 255, 255)" id="extra_hours" type="number" class="form-control" min="0" name="extra_hours" min='0' value="{{ $rents[0]->extra_Hours ?? 0 }}" readonly>
                                </div>
                            </div>      
                            
                            <div class="col-md-3 pl-1">
                                <div class="form-group">
                                    <label style="color: black;">
                                        <i class="fa-solid fa-money-bill-wave"></i> Total Price
                                    </label>
                                    <input style="color: black; background-color: rgb(255, 255, 255)" type="text" name='total_price' id='total_price' class="form-control" min='0' value="₱ {{$rents[0]->total_Price}}" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label style="color: black;">
                                        <i class="fas fa-money-check-alt"></i> Payment Status
                                    </label>
                                    <input style="color: black;background-color: rgb(255, 255, 255)" type="text" class="form-control" value="{{$rents[0]->payment_Status}}" readonly>
                                </div>
                            </div>
                        
                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label style="color: black;">
                                        <i class="fas fa-user"></i> Booking Confirmed by:
                                    </label>
                                    <input style="color: black;background-color: white" type="text" class="form-control" value="{{$rents[0]->employee->firstName}} {{$rents[0]->employee->lastName}}" readonly>
                                </div>
                            </div>
                        
                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label style="color: black;">
                                        <i class="fa-solid fa-dollar-sign"></i> Balance
                                    </label>
                                    <input style="color: black;background-color: rgb(255, 255, 255)" type="text" class="form-control" value="₱ {{$rents[0]->balance}}" id="balance" name='balance' readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">    
                                Vehicles Booked
                            </div>
                            <div class="col-md-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Vehicle Type</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vehicleTypesBooked as $vehicleTypeBooked)
                                    <tr>
                                        <td>{{ $vehicleTypeBooked->vehicleType->vehicle_Type }}</td>
                                        <td>{{ $vehicleTypeBooked->quantity }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                            </div>
                        </div>
                        <div class="row">
                            @foreach($vehiclesAssigned as $vehicleAssigned)
                                <div class="col-md-6">    
                                    <label for="vehicleDropdown_{{ $vehicleAssigned->vehicle->unitID }}">Vehicle Assigned:</label>
                                    <select class="form-control" id="vehicleDropdown_{{ $vehicleAssigned->vehicle->unitID }}" name="unitID[]"
                                            style="font-size: 18px">
                                        <option value="{{ $vehicleAssigned->vehicle->unitID }}">
                                            {{ $vehicleAssigned->vehicle->unitName }} {{ $vehicleAssigned->vehicle->registrationNumber }}
                                        </option>
                                        @foreach($availableVehicles as $vehicle)
                                            @if (!$vehiclesAssigned->contains('vehicle.unitID', $vehicle->unitID))
                                                <option value="{{ $vehicle->unitID }}">
                                                    {{ $vehicle->unitName }}  {{ $vehicle->registrationNumber }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="employeeDropdown_{{ $vehicleAssigned->employee->empID }}">Driver Assigned:</label>
                                    <select class="form-control" id="employeeDropdown_{{ $vehicleAssigned->employee->empID }}" name="empID[]"
                                            style="font-size: 18px">
                                        <option value="{{ $vehicleAssigned->employee->empID }}">
                                            {{ $vehicleAssigned->employee->firstName }} {{ $vehicleAssigned->employee->lastName }}
                                        </option>
                                        @foreach($availableDrivers as $driver)
                                            @if (!$vehiclesAssigned->contains('employee.empID', $driver->empID))
                                                <option value="{{ $driver->empID }}">
                                                    {{ $driver->firstName }} {{ $driver->lastName }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach
                        </div>
                        
                        
                        <br>
                        @if ($rents[0]->rent_Period_Status !== 'Completed' && $rents[0]->rent_Period_Status !== 'Cancelled')
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                        @endif
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function goBack() {
    window.location.href = "{{ route('employee.rental') }}";
}
</script>
@endsection
