@extends('layouts.index')

@section('content')

<div class="container1" style="padding: 20px; background: none; box-shadow: none; width: 100%">

    <!-- Add search and filter section on the right side -->
    <div class="row">
        <!-- Browse Vehicle text on the left -->
        <div class="col">
            <h1 style="text-align: center; font-size: 40px; font-weight: 700">Vehicles We Offer</h1>
            <p style="font-weight: 400">Please select the types of vehicle(s) you want to book </p>
        </div>
    </div>

    <hr>

    <div class="row mx-auto text-center justify-content-center">
        @foreach($vehicleTypes as $v)
        <div class="col-md-3 mb-4">
            <div class="vehicle-card" data-id="{{ $v->vehicle_Type_ID }}" style="border-radius:10px; background-color:white;">
                <div style="max-height: 250px; overflow: hidden;">
                    <img class="card-img-top img-fluid" src="{{ asset('storage/' . $v['pic']) }}" alt="Card image cap">
                </div>
                <div class="card-content">
                    <h5 class="card-title" style="font-size: 24px; margin-top: 10px;"><strong>{{ $v->vehicle_Type }}</strong></h5>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <form id="vehicleSelectionForm" action="{{ route('createbooking') }}" method="POST">
        @csrf
        <input type="hidden" id="selectedVehicleTypes" name="selectedVehicleTypes" value="">
        <button type="button" id="submitSelectionButton" class="btn btn-primary"
            style="margin-top: 10px; font-weight: 700; border-radius:10px; padding:15px 20px">Proceed to
            Booking</button>
    </form>

</div>

<!-- Modal for displaying the message -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel" style="color: orange"><i
                        class="fas fa-exclamation-circle"></i> Attention</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <p style="font-weight: 400">Please select at least one vehicle type.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript to handle vehicle selection
    const selectedVehicleTypes = [];

    document.querySelectorAll('.vehicle-card').forEach(card => {
        card.addEventListener('click', () => {
            const vehicleTypeId = card.getAttribute('data-id');

            // Toggle selection
            if (selectedVehicleTypes.includes(vehicleTypeId)) {
                selectedVehicleTypes.splice(selectedVehicleTypes.indexOf(vehicleTypeId), 1);
                card.style.backgroundColor = 'white'; // Deselect
            } else {
                selectedVehicleTypes.push(vehicleTypeId);
                card.style.backgroundColor = 'lightgreen'; // Select
            }
        });
    });

    document.getElementById('submitSelectionButton').addEventListener('click', () => {
        // Check if no vehicle type is selected
        if (selectedVehicleTypes.length === 0) {
            // Display the modal with a message
            $('#myModal').modal('show');
            return; // Do not submit the form
        }

        // Update the hidden form field with selected data
        document.getElementById('selectedVehicleTypes').value = selectedVehicleTypes.join(',');

        // Submit the form
        document.getElementById('vehicleSelectionForm').submit();
    });
</script>

@endsection
