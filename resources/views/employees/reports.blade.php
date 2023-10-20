@extends('layouts.empbar')

@section('title')
    TS | Reports
@endsection

@section('content')
<br><br>
<style>
    
    .filter-nav a.active {
        background-color: orangered; /* New background color for the active filter */
    }

    .filter-nav {
        text-align: center;
    }

    .filter-nav ul {
        list-style: none;
        padding: 0;
    }

    .filter-nav li {
        display: inline-block;
        margin-right: 10px;
    }

    .filter-nav a {
        text-decoration: none;
        background-color: #3498db;
        color: #fff;
        padding: 8px 15px;
        border-radius: 5px;
        transition: background-color 0.2s;
    }

    .filter-nav a:hover {
        background-color: rgba(255, 68, 0, 0.763);
    }

    .interactive-card {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .interactive-card h4 {
        margin-top: 0;
    }

    .interactive-card p {
        font-size: 17px;
    }

    .statistics-bar {
        height: 20px;
        background-color: #3498db;
        margin-top: 10px;
        border-radius: 5px;
    }

    .bar-container {
        background-color: #eee;
        border-radius: 5px;
        padding: 5px;
    }
</style>

<div class="container" style="padding: 20px;">
    <h2>Maintenance and Rental Reports</h2>
    <hr>
    <div class="filter-nav text-right" id="filter-nav-1" >
        <ul>
            <li><a href="#" data-filter="1D">1D</a></li>
            <li><a href="#" data-filter="5D">5D</a></li>
            <li><a href="#" data-filter="1M">1M</a></li>
            <li><a href="#" data-filter="6M">6M</a></li>
            <li><a href="#" data-filter="1Y">1Y</a></li>
            <li><a href="#" data-filter="MAX">MAX</a></li>
        </ul>
    </div>
    <br>
    <div class="row">
        <div class="col-md-4">
            <div class="card interactive-card">
                <div class="card-body">
                    <h4 class="card-title"><i class="fas fa-wrench"></i> Maintenance</h4>
                    <hr>
                    <p class="card-text">
                        <strong>Count:</strong> <span id="totalMaintenance">Loading...</span>
                        <div class="bar-container">
                            <div class="statistics-bar" id="maintenanceBar" style="width: 0;"></div>
                        </div>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card interactive-card">
                <div class="card-body">
                    <h4 class="card-title"><i class="fa-solid fa-car-side"></i> Rentals</h4>
                    <hr>
                    <p class="card-text">
                        <strong>Count:</strong> <span id="totalFleetRentals">Loading...</span>
                        <div class="bar-container">
                            <div class="statistics-bar" id="fleetRentalsBar" style="width: 0;"></div>
                        </div>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card interactive-card">
                <div class="card-body">
                    <h4 class="card-title"><i class="fa-solid fa-laptop-file"></i> Bookings</h4>
                    <hr>
                    <p class="card-text">
                        <strong>Count:</strong> <span id="totalBookings">Loading...</span>
                        <div class="bar-container">
                            <div class="statistics-bar" id="bookingBar" style="width: 0;"></div>
                        </div>
                    </p>
                </div>
            </div>
        </div>
        

        <div class="col-md-6">
            <div class ="card interactive-card">
                <div class="card-body">
                    <h4 class="card-title"><i class="fas fa-money-bill"></i> Revenue</h4>
                    <hr>
                    <p class="card-text" >
                        <strong>Revenue Earned:</strong> <span id="totalRevenue">Loading...</span> <br>
                        <strong>Downpayment Received:</strong><span id="downpaymentReceived">Loading...</span><br>
                        <strong>Money Remitted:</strong> <span id="moneyRemitted">Loading...</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="filter-nav text-right" id="filter-nav-2" >
        <ul>
            <li><a href="#" data-filter="1Month">1M</a></li>
            <li><a href="#" data-filter="6Months">6M</a></li>
            <li><a href="#" data-filter="1Year">1Y</a></li>
            <li><a href="#" data-filter="MAXYear">MAX</a></li>
        </ul>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card interactive-card">
                <div class="card-body">
                    <h4 class="card-title"><i class="fas fa-map-marker-alt"></i> Top Locations</h4>
                    <hr>
                    <table class="table" id="tariffTable">
                        <tbody>
                            <!-- Data will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card interactive-card">
                <div class="card-body">
                    <h4 class="card-title"><i class="fas fa-car"></i> Top Assigned Fleets</h4>
                    <hr>
                    <table class="table" id="assignedVehiclesTable">
   
                        <tbody>
                            <!-- Data will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>            
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const fetchDataUrl = '{{ route('fetchData') }}';
const fetchDataSecondFilterUrl = '{{ route('fetchDataSecondFilter') }}';

// Function to update sections based on the first filter
function updateSections(filter) {
    // Remove the 'active' class from all filters

    $.ajax({
        url: fetchDataUrl,
        type: 'POST',
        data: {
            filter: filter,
            _token: '{{ csrf_token() }}'
        },
        success: function (data) {
            if (data.error) {
                console.error(data.error);
            } else {
                // Add the 'active' class to the selected filter
                $(`[data-filter="${filter}"]`).addClass('active');

                // Update the Maintenance section
                $('#totalMaintenance').text(data.totalMaintenance);
                $('#maintenanceBar').css('width', data.maintenancePercentage + '%');

                // Update the Rentals section
                $('#totalFleetRentals').text(data.totalRentals);
                $('#fleetRentalsBar').css('width', data.rentalsPercentage + '%');

                // Update the Booking section
                $('#totalBookings').text(data.totalBookings);
                $('#bookingBar').css('width', data.bookingPercentage + '%');

                // Update the Revenue section
                $('#totalRevenue').text(data.totalRevenue);
                $('#revenueBar').css('width', data.revenuePercentage + '%');

                // Optionally, update additional sections if needed
                $('#downpaymentReceived').text(data.downpaymentReceived);
                $('#moneyRemitted').text(data.moneyRemitted);
            }
        },
        error: function (error) {
            console.error('Failed to fetch data:', error);
        }
    });
}

// Function to update sections based on the second filter
function updateSectionsSecondFilter(filter) {
    // Remove the 'active' class from all filters in the second filter group

    $.ajax({
        url: fetchDataSecondFilterUrl,
        type: 'POST',
        data: {
            filter: filter,
            _token: '{{ csrf_token() }}'
        },
        success: function (data) {
            if (data.error) {
                console.error(data.error);
            } else {
                // Add the 'active' class to the selected filter
                $(`[data-filter="${filter}"]`).addClass('active');

                // Update the Tariff section
                const tariffTable = $('#tariffTable tbody');
                tariffTable.empty(); // Clear existing data

                // Create an object to store the counts for each location and booking type
                const locationCounts = {};

                for (const bookingType in data.topLocations) {
                    const bookingCount = data.topLocations[bookingType];
                    const [locationName, type] = bookingType.split('|'); // Split bookingType into locationName and type

                    if (!locationCounts[locationName]) {
                        locationCounts[locationName] = {};
                    }

                    locationCounts[locationName][type] = bookingCount;
                }

                // Create the table header for Tariff section
                tariffTable.append(`
                    <tr class="text-center">
                        <th class="col-md-5">Location</th>
                        <th>Rent Count</th>
                        <th>Pick-Up/Drop-Off Count</th>
                    </tr>
                `);

                // Populate the table with location and booking type counts
                for (const locationName in locationCounts) {
                    const counts = locationCounts[locationName];
                    tariffTable.append(`
                        <tr class="text-center">
                            <td>${locationName}</td>
                            <td>${counts['Rent'] || 0}</td>
                            <td>${counts['Pickup\/Dropoff'] || 0}</td>
                        </tr>
                    `);
                }

                // Update the Assigned Vehicles section
                const assignedVehiclesTable = $('#assignedVehiclesTable tbody');
                assignedVehiclesTable.empty(); // Clear existing data

                // Add the header for the Assigned Vehicles section
                assignedVehiclesTable.append(`
                    <tr class="text-center">
                        <th class="col-md-5">Vehicle</th>
                        <th>Plate Number</th>
                        <th>Assignment Count</th>
                    </tr>
                `);

                data.topVehicles.forEach((fleet) => {
                    // Append a row for each fleet and its assigned vehicles
                    assignedVehiclesTable.append(`
                        <tr class="text-center">
                            <td>${fleet.unitName}</td>
                            <td>${fleet.registrationNumber}</td>
                            <td>${fleet.assignment_count}</td>
                        </tr>
                    `);
                });
            }
        },
        error: function (error) {
            console.error('Failed to fetch data for the second filter:', error);
        }
    });
}

// Click event handler for the "View Fleet" button
$(document).on('click', '.view-fleet-button', function () {
    const fleetId = $(this).data('fleet-id');
    
    // Navigate to a different page with the fleet ID
    window.location.href = '/fleetReport/' + fleetId; // Replace '/fleet/' with the actual URL you want to navigate to
});


// Click event handler for the first filter buttons
$('.filter-nav:eq(0) a').click(function (e) {
    e.preventDefault();
    const filter = $(this).data('filter');
    updateSections(filter);

    // Remove the 'active' class from all first filter buttons
    $('.filter-nav:eq(0) a').removeClass('active');
    
    // Add the 'active' class to the clicked button
    $(this).addClass('active');
});

// Click event handler for the second filter buttons
$('.filter-nav:eq(1) a').click(function (e) {
    e.preventDefault();
    const filter = $(this).data('filter');
    updateSectionsSecondFilter(filter);

    // Remove the 'active' class from all second filter buttons
    $('.filter-nav:eq(1) a').removeClass('active');
    
    // Add the 'active' class to the clicked button
    $(this).addClass('active');
});

// Initial update for the first filter group
const initialFilter = '1D';
updateSections(initialFilter);

// Initial update for the second filter group
const initialFilter1 = '1Month';
updateSectionsSecondFilter(initialFilter1);

// Function to refresh data for the first filter
function refreshFirstFilterData() {
    const activeFilter = $('.filter-nav:eq(0) a.active').data('filter');
    updateSections(activeFilter);
}

// Function to refresh data for the second filter
function refreshSecondFilterData() {
    const activeFilter = $('.filter-nav:eq(1) a.active').data('filter');
    updateSectionsSecondFilter(activeFilter);
}

// Refresh the data every X milliseconds for both filters
setInterval(refreshFirstFilterData, 30000);
setInterval(refreshSecondFilterData, 30000);


</script>
@endsection
