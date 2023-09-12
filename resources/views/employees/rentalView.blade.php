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
        @endif

        @if(session('error'))
        <div class="custom-error-message alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

<div class="content" >
    <div class="row">
      <div class="col-md-10" >
        <div class="card">
          <div class="card-header">
            <h5 class="title">Rent Information</h5> <hr> ID Reference: <span><strong> Rent Number: {{$rental->rentID}}</strong></span>
          </div>
          <div class="card-body" style="font-size: 15px;">
            <form method="POST" action="{{ route('rental.update', $rental->rentID) }}">
                @csrf
                @method('PUT')
              <div class="row">
                <div class="col-md-6 pr-1">
                  <div class="form-group">
                    <label style="color: black;">Client Name</label>
                    <input type="text" class="form-control" value='{{$bookings[0]->customer->firstName}} {{$bookings[0]->customer->lastName}}' readonly >
                  </div>
                </div>
                <div class="col-md-6 pl-1">
    <div class="form-group">
        <label style="color: black">Fleet</label>
        <select class="form-control" name="vehicle_id">
            @foreach($availableVehicles as $vehicle)
                <option value="{{ $vehicle->id }}" {{ $vehicle->id === $bookings[0]->unitID ? 'selected' : '' }}>
                    {{ $vehicle->unitName }} - {{ $vehicle->registrationNumber }}
                </option>
            @endforeach
        </select>
    </div>
</div>

              </div>
              
              <div class="row">
                <div class="col-md-8 pr-1">
                    <div class="form-group">
                        <label style="color: black;">Location</label>
                        <select class="form-control" name="tariff_id">
                            @foreach($tariffLocations as $tariffID => $location)
                                <option value="{{ $tariffID }}" {{ $tariffID === $bookings[0]->tariffID ? 'selected' : '' }}>
                                    {{ $location }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-4 pl-1">
                    <div class="form-group">
                      <label style="color: black;">Mobile Number</label>
                      <input type="text" class="form-control" value="{{$bookings[0]->mobileNum}}" >
                    </div>
                  </div>
                </div>

                <div class="row">
                    <div class="col-md-3 pr-1">
                        <div class="form-group">
                            <label style="color: black;">Pick-Up Date</label>
                            <input type="date" class="form-control" name="pickup_date" value="{{ \Carbon\Carbon::parse($bookings[0]->startDate)->format('Y-m-d') }}">
                        </div>
                    </div>
                    
                    <div class="col-md-3 pl-1">
                        <div class="form-group">
                            <label style="color: black;">Pick-Up Time</label>
                            <input type="time" class="form-control" name="pickup_time" value="{{ \Carbon\Carbon::parse($bookings[0]->startDate)->format('H:i') }}">
                        </div>
                    </div>
                    
                    <div class="col-md-3 pr-1">
                        <div class="form-group">
                            <label style="color: black;">Pick-Up Date</label>
                            <input type="date" class="form-control" name="pickup_date" value="{{ \Carbon\Carbon::parse($bookings[0]->endDate)->format('Y-m-d') }}">
                        </div>
                    </div>
                    
                    <div class="col-md-3 pl-1">
                        <div class="form-group">
                            <label style="color: black;">Pick-Up Time</label>
                            <input type="time" class="form-control" name="pickup_time" value="{{ \Carbon\Carbon::parse($bookings[0]->endtDate)->format('H:i') }}">
                        </div>
                    </div>
                </div>

                    <div class="row">
                        <div class="col-md-12 ">
                          <div class="form-group">
                            <label style="color: black;">Pick-Up Address</label>
                            <input type="text" class="form-control" value="{{$bookings[0]->pickUp_Address}}">
                          </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label style="color: black;">Note</label>
                                <textarea class="form-control" rows="4" value="{{$bookings[0]->note}}"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 pr-1">
                          <div class="form-group">
                            <label style="color: black;">Gcash Reference Number</label>
                            <input type="text" class="form-control" value="{{$bookings[0]->gcash_RefNum}}" readonly>
                          </div>
                        </div>
                        <div class="col-md-3 pl-1">
                            <div class="form-group">
                              <label style="color: black;">Downpayment Amount</label>
                              <input type="text" class="form-control" value="{{$bookings[0]->downpayment_Fee}}" readonly>
                            </div>
                          </div>
                          <div class="col-md-3 pl-1">
                            <div class="form-group">
                              <label style="color: black;">Subtotal</label>
                              <input type="text" class="form-control" readonly value="{{$bookings[0]->subtotal}}">
                            </div>
                          </div>
                          <div class="col-md-3 pl-1">
                            <div class="form-group">
                                <label style="color: black;">Status</label>
                                <select class="form-control" name="status">
                                    <option value="Pending" {{ $bookings[0]->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Approved" {{ $bookings[0]->status === 'Approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="Canceled" {{ $bookings[0]->status === 'Canceled' ? 'selected' : '' }}>Canceled</option>
                                </select>
                            </div>
                        </div>                        
                        </div>

                        <div class="row">
                            <div class="col-md-3 pr-1">
                              <div class="form-group">
                                <label style="color: black;">Booking ID</label>
                                <input type="text" class="form-control" readonly  value="{{$bookings[0]->reserveID}}">
                              </div>
                            </div>
                            <div class="col-md-3 pl-1">
                                <div class="form-group">
                                    <label style="color: black;">Driver Assigned</label>
                                    <select class="form-control" name="driver_assigned">
                                        @foreach($drivers as $driver)
                                            <option value="{{ $driver->empID }}" {{ $driver->empID === $rents[0]->driver->empID ? 'selected' : '' }}>
                                                {{ $driver->firstName }} {{ $driver->lastName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3 pl-1">
                                <div class="form-group">
                                    <label style="color: black;">Rental Status</label>
                                    <select class="form-control" name="rental_status">
                                        <option value="Booked" {{ $rents[0]->rent_Period_Status === 'Booked' ? 'selected' : '' }}>Booked</option>
                                        <option value="Ongoing" {{ $rents[0]->rent_Period_Status === 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                        <option value="Completed" {{ $rents[0]->rent_Period_Status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>
                            </div>
                            
                              <div class="col-md-3 pl-1">
                                <div class="form-group">
                                  <label style="color: black;">Extra Hours</label>
                                  <input type="number" class="form-control" min="0">
                                </div>
                              </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 pr-1">
                                  <div class="form-group">
                                    <label style="color: black;">Payment Status</label>
                                    <input type="text" class="form-control"  value="{{$rents[0]->payment_Status}}" readonly>
                                  </div>
                                </div>
                                <div class="col-md-4 pl-1">
                                    <div class="form-group">
                                      <label style="color: black;">Total Price</label>
                                      <input type="text" class="form-control" value="{{$rents[0]->total_Price}}" readonly>
                                    </div>
                                  </div>
                                  <div class="col-md-4 pl-1">
                                    <div class="form-group">
                                      <label style="color: black;">Balance</label>
                                      <input type="text" class="form-control" value="{{$rents[0]->balance}}" readonly >
                                    </div>
                                  </div>
                                </div>

            

                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <button type="button" class="btn btn-danger" onclick="goBack()">Cancel</button>
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
        window.history.back();
    }
</script>
@endsection
