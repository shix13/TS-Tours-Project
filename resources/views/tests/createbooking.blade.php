@extends('layouts.index')

@section('content')
<div class="container">
<img src="{{ asset('images/input-booking.png') }}" style="border-radius: 10px;">
<hr>
    <div class="row container">
        <div class="container1" style="display: inline-block;">
            <h4 style="font-weight: 700">Booking Details</h4> <br>
            <form method="POST" action="{{ route('processbookingreq') }}">
            @csrf
            <div class="container">
                <div class="row">
                    <div class="col">
                        <b>Booking type</b>
                    </div>
                    <div class="col text-center">
                        <div class="row">
                            <input type="radio" id="type1" name="bookingType" value="Rent" checked="checked" onchange="toggleEndDate()">
                            <label for="type1">Rent</label>
                        </div>
                        <div class="row">
                            <input type="radio" id="type2" name="bookingType" value="Pickup/Dropoff" onchange="toggleEndDate()">
                            <label for="type2">Pickup/Dropoff</label>
                        </div>
                    </div>
                </div>

                @foreach ($vehicleTypes as $vehicleType)
                <br>
                    @php
                        $quantityInputName = "TypeQuantity[$vehicleType->vehicle_Type_ID]";
                        $smallestPaxCount = $smallestPaxCounts[$vehicleType->vehicle_Type_ID] ?? 0;
                    @endphp
                    
                    <input type="hidden" name="{{ $quantityInputName }}_minPax" value="{{ $smallestPaxCount }}">
                    
                @endforeach
                <input  type="hidden" name="countVtype" id="countVtype" value="{{ count($vehicleTypes) }}"> 
                <input type="hidden" name="Paxcounter" id="Paxcounter" >
                
                <div class="row">
                    <div class="col">
                        <b>Vehicle Type</b>
                    </div>
                    <div class="col">
                        <b>Quantity</b>
                    </div>
                </div>
                @foreach($vehicleTypes as $vehicleType)
                <div class="row">
                    <div class="col" style="padding: 5px;">
                        {{ $vehicleType->vehicle_Type }}
                    </div>
                    <div class="col" style="padding: 5px;">
                        <input type="number" name="TypeQuantity[{{ $vehicleType->vehicle_Type_ID }}]" value="1" min="1" class="quantity-input">
                    </div>                                     
                </div>
                @endforeach
                <div id="recommendation"></div>
                <br>
                <div class="row">
                    <div class="col">
                        <i class="fas fa-map-marker-alt"></i> Location
                    </div>
                    <div class="col">
                        <select id="location" name="location" style="width: 100%;padding:5px" required>
                            <option value="" selected disabled>--Select Location--</option>
                            @foreach($tariffData as $t)
                                <option data-booking-types="{{ json_encode($t->booking_types) }}" 
                                    data-rate-per-day="{{ $t->rate_Per_Day }}" 
                                    data-rentPerDayHrs="{{ $t->rentPerDayHrs }}"
                                    data-rent-per-hour="{{ $t->rent_Per_Hour }}"
                                    data-do-pu="{{ $t->do_pu }}" 
                                    data-location="{{ $t->location }}"
                                    value="{{ $t->location }}">
                                {{ $t->location }}</option>
                            @endforeach
                        </select>                        
                    </div>
                </div>
                
              
                <input type="hidden" id="rate_Per_Day" value="{{ $tariffData->first()->rate_Per_Day }}" readonly>
                <input type="hidden" id="do_pu" value="{{ $tariffData->first()->do_pu }}" readonly>
                <input type="hidden" id="rent_Per_Hour" value="{{ $tariffData->first()->rent_Per_Hour }}" readonly>
                <input type="hidden" id="rentPerDayHrs" value="{{ $tariffData->first()->rentPerDayHrs }}" readonly>
                <input type="hidden"  id="subtotalInput" name="subtotalInput"  readonly>
                

                <br>
                <div class="row container1" style="background: none;box-shadow:none;">
                    <div class="col">
                        <i class="fas fa-calendar-alt"></i> Schedule Date  <hr>
                        <div class="row">
                        <div class="col">
                            Schedule Date <input type="date" name="StartDate" id="StartDate" required>
                        </div>
                        <div class="col" id="enddatecol">
                            Return Date <input type="date" name="EndDate" id="EndDate" required>
                        </div>
                        
                        </div>
                    </div>
                </div>

               <br>
                <div class="col">
                    Pax
                    <input type="number" name="Pax" id="Pax" min="1" required> 
                  
                </div>
                <hr>
            
                <div class="row container1" style="background: none;box-shadow:none;">
                    <div class="col">
                        <i class="fas fa-user"></i> Contact Person
                        <div class="row">
                            <div class="col">
                                First Name <input type="text" name="FirstName" required>
                            </div>
                            <div class="col">
                                Last Name <input type="text" name="LastName" required>
                            </div>
                        </div> <hr>
                    </div> 
                </div>
                <br>
             
                <div class="row" style="justify-content:center">
                    <div class="col-md-4">
                        <i class="fas fa-envelope"></i> Email Address: 
                    </div>
                    <div class="col-md-4">
                        <input type="email" name="Email" required>
                    </div>
                </div>
                <div class="row" style="justify-content:center">
                    <div class="col-md-4" >
                        <i class="fas fa-phone"></i> Phone Number
                    </div>
                    <div class="col-md-4" >
                        <input name="MobileNum" required>
                    </div>
                </div>
                <div class="row" style="justify-content:center">
                    <div class="col-md-4">
                        <i class="fas fa-map-pin"></i> Pickup Address
                    </div>
                    <div class="col-md-4">
                        <input name="PickUpAddress" required>
                    </div>
                </div> 
                <div class="row"  style="justify-content:center">
                    <div class="col-md-4" >
                        <i class="fas fa-clock"></i> Pickup Time
                    </div>
                    <div class="col-md-4">
                        <input type="time" id="PickupTime" name="PickupTime" style="width: 175px" required>
                    </div>
                </div>
          
                 <br>
                <div class="col">
                    <div class="col">
                        <i class="fas fa-sticky-note"></i> Additional Notes
                    </div>
                    <div class="col-md-12">
                        <textarea class="form-control" rows="5" name='Note' style="background:white;border:1px solid black"></textarea>
                    </div>
                </div>
                <br>
                <div class="container1" style="background: lightgray;border-radius:0px">
                    <div id="location-info">
                        <div class="row"> 
                            <div class="col">
                                <h6>Pricing Info</h6>
                                
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="col">
                            </div>
                        </div>
                    </div>
                </div>
                    <br>
                <div class="container1" >
                    <div class="row"> 
                        <div class="col">
                            <h6>Estimated Cost</h6>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col">
                            Vehicle Count: <span id="vCount">0</span> <br>
                           
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            Subtotal: ₱<span id="subtotal">0.00</span> <br> 
                            Downpayment Fee (10%): ₱<span id="downpayment">0.00</span>
                        </div>
                    </div>
                </div> 
                <div class="row">
                    <div class="col text-center">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-bookmark"></i> <strong>Book Now </strong></button>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-center">
                        <input type="checkbox">
                        <button type="button" class="btn btn-link" data-toggle="modal" data-target="#termsModal" style="color: #f96332;font-size:13px;">
                            <u>Terms and Conditions</u>
                        </button>
                    </div>
                </div>                
            </div>
            
            </form>
        </div>
    </div>
</div>

<div class="modal" id="recommendModal">
    <p>Please add more quantity to accommodate all passengers.</p>
    <button class="close-btn" onclick="closeRecommendModal()">Close</button>
</div>

<div class="overlay" id="overlay"></div>

@endsection

@include('customers.termsModal')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var startDateInput = document.getElementById('StartDate');
        var endDateInput = document.getElementById('EndDate');
        var currentDate = new Date();
        currentDate.setDate(currentDate.getDate() + 1);
        var isoDate = currentDate.toISOString().split('T')[0];
        startDateInput.min = isoDate;
        var checkbox = document.querySelector('input[type="checkbox"]');
        var bookNowButton = document.querySelector('button[type="submit"]');
        bookNowButton.disabled = true;
         
        checkbox.addEventListener('change', function () {
            var enteredPax = parseInt(document.getElementById('Pax').value) || 0;
            var totalMinPax = parseInt(document.getElementById('Paxcounter').value) || 0;
            var bookNowButton = document.querySelector('button[type="submit"]');

            if (totalMinPax >= enteredPax && checkbox.checked) {
                bookNowButton.disabled = false;
            } else {
                bookNowButton.disabled = true;
                alert('Notice: Please increase vehicle quantity to accommodate all passengers.')
            }
        });

        startDateInput.addEventListener('change', function () {
            var selectedStartDate = new Date(startDateInput.value);
            selectedStartDate.setDate(selectedStartDate.getDate() );
            var minEndDate = selectedStartDate.toISOString().split('T')[0];
            endDateInput.min = minEndDate;
            endDateInput.value = '';
            if (!checkbox.checked) {
                bookNowButton.disabled = true;
            }
        });

        $('#location').change(updateLocationInfo);

        document.querySelectorAll('input[name="bookingType"]').forEach(function (radioButton) {
            radioButton.addEventListener('change', updateLocationInfo);
        });
        
        

        document.getElementById('location').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('rate_Per_Day').value = selectedOption.getAttribute('data-rate-per-day');
            document.getElementById('do_pu').value = selectedOption.getAttribute('data-do-pu');
            document.getElementById('rent_Per_Hour').value = selectedOption.getAttribute('data-rent-per-hour');
            document.getElementById('rentPerDayHrs').value = selectedOption.getAttribute('data-rentPerDayHrs');  
            calculateSubtotal();
        });

        const inputElements = document.querySelectorAll('input, select');
        inputElements.forEach((input) => {
            input.addEventListener('input', calculateSubtotal);
        });
        
        function validateInputs() {
            const bookingType = document.querySelector('input[name="bookingType"]:checked');
            const bookingTypeValue = bookingType.value;
            const location = document.getElementById('location').value;
            const startDate = document.getElementById('StartDate').value;
            const endDate = document.getElementById('EndDate').value;
            const vehicleTypeInputs = document.querySelectorAll('input[name^="TypeQuantity"]');
            if (bookingTypeValue === 'Rent') {
                if (!bookingType || !location || !startDate || !endDate || vehicleTypeInputs.length === 0 || Array.from(vehicleTypeInputs).some(input => input.value === '')) {
                    return false;
                }
            } else if (bookingTypeValue === 'Pickup/Dropoff') {
                if (!bookingType || !location || !startDate || vehicleTypeInputs.length === 0 || Array.from(vehicleTypeInputs).some(input => input.value === '')) {
                    return false;
                }
            }
            return true;
        }

        function calculateSubtotal() {
    if (validateInputs()) {
      
        var bookingType = document.querySelector('input[name="bookingType"]:checked').value;
        const ratePerDayInput = document.getElementById('rate_Per_Day');
        const rentPerHourInput = document.getElementById('rent_Per_Hour');
        const rentPerDayHrsInput = document.getElementById('rentPerDayHrs');
        var startDate = new Date(document.getElementById('StartDate').value);
        var PickupTime = document.getElementById('PickupTime').value;

        // Splitting hours and minutes from PickupTime
        var [hours, minutes] = PickupTime.split(':');

        // Setting the time of startDate to the time from PickupTime
        startDate.setHours(hours, minutes, 0, 0);

        // Creating a new Date object with the adjusted startDate
        var StartDateTime = new Date(startDate);

        var endDate, daysToRent;
        var totalVehicleQuantity = 0; // Initialize total vehicle quantity

        // Iterate through each quantity input for vehicle types
        document.querySelectorAll('.quantity-input').forEach(function (quantityInput) {
            totalVehicleQuantity += parseInt(quantityInput.value) || 0;
        });

       

        if (bookingType === 'Rent') {
            endDate = new Date(document.getElementById('EndDate').value);
            // Calculate daysToRent
            var daysToRent = Math.floor((endDate - StartDateTime) / (1000 * 60 * 60 * 24)) + 1;
            
            console.log(daysToRent)
           
            
                // Calculate subtotal for one day
                var subtotal = parseFloat(ratePerDayInput.value) * totalVehicleQuantity * daysToRent;
                var downpayment = subtotal * 0.10;
                document.getElementById('vCount').textContent = totalVehicleQuantity;
                document.getElementById('subtotal').textContent = subtotal.toFixed(2);
                document.getElementById('downpayment').textContent = downpayment.toFixed(2);
                document.getElementById('subtotalInput').value = subtotal.toFixed(2);

                // If booking is for more than 1 day
                endDate.setHours(12, 0, 0, 0);
                var timeToRent = endDate.getTime() - StartDateTime.getTime();
                
                var hoursToRent = timeToRent / (1000 * 60 * 60);

        } else if (bookingType === 'Pickup/Dropoff') {
            // Logic for Pickup/Dropoff booking type
            daysToRent = 1;
            var doPuInput = document.getElementById('do_pu');
            var tariff = parseFloat(doPuInput.value);

            // Calculate subtotal
            var subtotal = (tariff * daysToRent) * totalVehicleQuantity * daysToRent;
            var downpayment = subtotal * 0.10;
            document.getElementById('subtotal').textContent = subtotal.toFixed(2);
            document.getElementById('downpayment').textContent = downpayment.toFixed(2);
            
            document.getElementById('subtotalInput').value = subtotal.toFixed(2);
            document.getElementById('vCount').textContent = totalVehicleQuantity;
        } else {
            // Handle other cases if needed
        }
    } else {
        // Handle case when inputs are not valid
    }
}





            document.querySelectorAll('input[name="bookingType"]').forEach(function (radioButton) {
                radioButton.addEventListener('change', updateLocationInfo);
            });

            var tariffData = @json($tariffData);

            function toggleEndDate() {
        const rentRadio = document.getElementById('type1');
        const pickupDropoffRadio = document.getElementById('type2');
        const endDateCol = document.getElementById('enddatecol');
        const endDateInput = document.getElementById('EndDate');
        const locationDropdown = document.getElementById('location');
        const ratePerDayInput = document.getElementById('rate_Per_Day');
        const doPuInput = document.getElementById('do_pu');
        const rentPerHourInput = document.getElementById('rent_Per_Hour');
        const rentPerDayHrsInput = document.getElementById('rentPerDayHrs');


        if (pickupDropoffRadio.checked) {
            endDateCol.style.display = 'none';
            endDateInput.setAttribute('disabled', true);
            endDateInput.removeAttribute('required');
            locationDropdown.innerHTML = '';

            const placeholderOption = document.createElement('option');
            placeholderOption.text = '--Select Location--';
            placeholderOption.value = ''; 
            locationDropdown.add(placeholderOption);
        
            tariffData.forEach(function (tariff) {
                if (tariff.do_pu) {
                    const option = document.createElement('option');
                    option.text = tariff.location;
                    option.value = tariff.location;
                    option.setAttribute('data-booking-types', JSON.stringify(tariff.booking_types));
                    option.setAttribute('data-rate-per-day', tariff.rate_Per_Day);
                    option.setAttribute('data-do-pu', tariff.do_pu);
                    option.setAttribute('data-rent-per-hour', tariff.rent_Per_Hour);
                    option.setAttribute('data-rentPerDayHrs', tariff.rentPerDayHrs);
                    locationDropdown.add(option);
                }
            });
        
            const selectedLocation = locationDropdown.options[locationDropdown.selectedIndex];

            ratePerDayInput.value = selectedLocation.getAttribute('data-rate-per-day');
            doPuInput.value = selectedLocation.getAttribute('data-do-pu');
            rentPerHourInput.value = selectedLocation.getAttribute('data-rent-per-hour');
            rentPerDayHrsInput.value = selectedLocation.getAttribute('data-rentPerDayHrs');      
            updateLocationInfo();  

        } else {
            endDateCol.style.display = 'block';
            endDateInput.removeAttribute('disabled');
            endDateInput.setAttribute('required', true);
            locationDropdown.innerHTML = '';

            const placeholderOption = document.createElement('option');
            placeholderOption.text = '--Select Location--';
            placeholderOption.value = ''; 
            locationDropdown.add(placeholderOption);
            
            tariffData.forEach(function (tariff) {
                if (tariff.rate_Per_Day) {
                    const option = document.createElement('option');
                    option.text = tariff.location;
                    option.value = tariff.location;
                    option.setAttribute('data-booking-types', JSON.stringify(tariff.booking_types));
                    option.setAttribute('data-rate-per-day', tariff.rate_Per_Day);
                    option.setAttribute('data-do-pu', tariff.do_pu);
                    option.setAttribute('data-rent-per-hour', tariff.rent_Per_Hour);
                    option.setAttribute('data-rentPerDayHrs', tariff.rentPerDayHrs);
                    locationDropdown.add(option);
                }
            });

            const selectedLocation = locationDropdown.options[locationDropdown.selectedIndex];

            ratePerDayInput.value = selectedLocation.getAttribute('data-rate-per-day');
            doPuInput.value = selectedLocation.getAttribute('data-do-pu');
            rentPerHourInput.value = selectedLocation.getAttribute('data-rent-per-hour');
            rentPerDayHrsInput.value = selectedLocation.getAttribute('data-rentPerDayHrs');
            updateLocationInfo();
        }
    }

    const pickupTimeInput = document.getElementById('PickupTime');
    pickupTimeInput.addEventListener('input', function () {
        calculateSubtotal();
    });

        const pickupDropoffRadio = document.getElementById('type2');
        const rentRadio = document.getElementById('type1');

        pickupDropoffRadio.addEventListener('change', toggleEndDate);
        rentRadio.addEventListener('change', toggleEndDate);

        updateLocationInfo();

        function updateLocationInfo() {
            const selectedType = document.querySelector('input[name="bookingType"]:checked').value;
            const selectedLocation = $('option:selected', '#location');
            const location = selectedLocation.data('location');  // Corrected attribute name
            const ratePerDay = selectedLocation.data('rate-per-day');
            const hours = selectedLocation.data('rentperdayhrs');
            const rateExtra = selectedLocation.data('rent-per-hour');
            const doPu = selectedLocation.data('do-pu');
            if (selectedType == 'Rent') {
                $('#location-info').html(`
                    <div class="row"> 
                        <div class="col">
                            <h5>Price Info</h5>
                           <hr>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col">
                            Rent Rate  (${hours} hours): ₱${ratePerDay} <br>
                           <hr>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col">
                            <h6>   Note: Succeeding Rate Per Hour: ₱${rateExtra}</h6>
                           <hr>
                        </div>
                    </div>
                    
                `);
            } else if (selectedType == 'Pickup/Dropoff') {
                $('#location-info').html(`
                    <div class="row"> 
                        <div class="col">
                            <h6>Price Info</h6>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col">
                            Dropoff/Pickup Rate: ₱${doPu} <br>
                            Note: Succeeding Rate Per Hour: ₱${rateExtra} <br>
                        </div>
                    </div>
                `);
            }
        }


        function updatePaxCounter() {
            var paxCounterInput = document.getElementById('Paxcounter');
            var totalMinPax = 0;
            var vehicleTypeCount = document.querySelector('input[name="countVtype"]').value;
            var minVehicleTypeId = Math.min.apply(
                null,
                Array.from(document.querySelectorAll('input[name^="TypeQuantity"]')).map(function (input) {
                    return parseInt(input.name.match(/\[(\d+)\]/)[1]);
                })
            );
            for (var i = minVehicleTypeId; i < minVehicleTypeId + parseInt(vehicleTypeCount); i++) {
                var quantityInputName = "TypeQuantity[" + i + "]";
                var quantityInput = document.querySelector('input[name="' + quantityInputName + '"]');
                var minPaxInput = document.querySelector('input[name="' + quantityInputName + '_minPax"]');
                if (!quantityInput || !minPaxInput) {
                    console.error('Error: Quantity input or Min Pax input not found for', quantityInputName);
                    continue;
                }
                var quantity = parseInt(quantityInput.value) || 0;
                var minPax = parseInt(minPaxInput.value) || 0;
                totalMinPax += quantity * minPax;
            }
            paxCounterInput.value = totalMinPax;
            var enteredPax = parseInt(document.getElementById('Pax').value) || 0;
            var checkbox = document.querySelector('input[type="checkbox"]');
            var bookNowButton = document.querySelector('button[type="submit"]');
            
            if (totalMinPax >= enteredPax && checkbox.checked) {
                bookNowButton.disabled = false;
            } else {
                bookNowButton.disabled = true;
            }

            if (totalMinPax < enteredPax) {
                showRecommendation('Please add more quantity to accommodate all passengers.');
            } else {
                hideRecommendation();
            }
        }
        function showRecommendation(message) {
            var recommendationElement = document.getElementById('recommendation');
            recommendationElement.textContent = message;
            recommendationElement.style.color = 'red';
            recommendationElement.style.display = 'block';
        }
        function hideRecommendation() {
            var recommendationElement = document.getElementById('recommendation');
            recommendationElement.style.display = 'none';
        }
        const quantityInputs = document.querySelectorAll('input[name^="TypeQuantity"]');
        const paxInput = document.getElementById('Pax');
        quantityInputs.forEach((quantityInput) => {
            quantityInput.addEventListener('input', function () {
                updatePaxCounter();
            });
        });
        paxInput.addEventListener('input', function () {
            updatePaxCounter();
        });
        document.getElementById('Pax').addEventListener('input', updatePaxCounter);
       
        @foreach ($vehicleTypes as $vehicleType)
        @php
        $quantityInputName = "TypeQuantity[$vehicleType->vehicle_Type_ID]";
        @endphp
        document.querySelector('input[name="{{ $quantityInputName }}"]').addEventListener('input', updatePaxCounter);
        @endforeach
    });
    function openRecommendModal() {
        document.getElementById('recommendModal').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';
    }

    function closeRecommendModal() {
        document.getElementById('recommendModal').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    }
</script>

