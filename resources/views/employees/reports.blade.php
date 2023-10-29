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
        background-color: rgba(209, 210, 212, 0.099);
        border: 2px solid lightslategray;
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
        margin-top: 0px;
        border-radius: 5px;
    }

    .bar-container {
        background-color: #eee;
        border-radius: 5px;
        border: 1px solid gray;
    }
</style>

<div class="container" style="padding: 20px;">
    <h2 style="font-weight:700"><i class="fa-solid fa-laptop-file"></i> Maintenance and Rental Reports</h2>
    <hr>
    <button id="generatePdfButton">Generate PDF</button>

    <div class="filter-nav text-right" id="filter-nav-1" >
        <ul>
            <li><a href="#" data-filter="1D">Today</a></li>
            <li><a href="#" data-filter="5D">5D</a></li>
            <li><a href="#" data-filter="1M">1M</a></li>
            <li><a href="#" data-filter="6M">6M</a></li>
            <li><a href="#" data-filter="1Y">1Y</a></li>
            <li><a href="#" data-filter="MAX">MAX</a></li>
        </ul>
    </div>
    <br>
    <div class="row">
        <div class="col-md-3">
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
        
        <div class="col-md-3">
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

        <div class="col-md-3">
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

        <div class="col-md-3">
            <div class="card interactive-card">
                <div class="card-body">
                    <h4 class="card-title"><i class="fa-solid fa-star"></i> Rating</h4>
                    <hr>
                    <p class="card-text">
                        <strong>Average:</strong> <span id="ratingAverageValue">Loading...</span>/5
                        <div class="bar-container">
                            <div class="statistics-bar" id="ratingBar" style="width: 0;"></div>
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
                        
                        <strong>Downpayment Received:</strong>₱ <span id="downpaymentReceived">Loading...</span><br>
                        <strong>Amount Remitted:</strong> ₱ <span id="moneyRemitted">Loading...</span> <br>
                        <strong>Total Amount Earned:</strong> ₱ <span id="totalRevenue">Loading...</span> <br>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <img src="{{ asset('images/Reports Grapiks.png') }}" alt="Reports Grapiks">
            </div>
    </div>
    
    <hr>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card interactive-card">
                <div class="card-body">
                    <h4 class="card-title" style="font-weight: 700"><i class="fas fa-map-marker-alt"></i> Top Locations</h4>
                    <hr>
                    <table class="table" id="topLocationsTable">
                        <tbody style="font-size:20px;font-weight:400">
                            <!-- Data will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card interactive-card">
                <div class="card-body">
                    <h4 class="card-title" style="font-weight: 700"><i class="fas fa-car"></i> Top Active Assigned Fleets </h4></p>
                    <hr>
                    <table class="table" id="assignedVehiclesTable">
   
                        <tbody style="font-size:20px;font-weight:400">
                            <!-- Data will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>            
        </div>
        <div class="col-md-12">
            <div class="card interactive-card">
                <div class="card-body">
                    <h4 class="card-title" style="font-weight: 700"><i class="fa-solid fa-car-side"></i> Fleet Schedules Created </h4>
                    <hr>
                    <table class="table" id="topVehiclesTable">
   
                        <tbody style="font-size:20px;font-weight:400">
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
let globalData = null;
const fetchDataUrl = '{{ route('fetchData') }}';

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

                if (data.secondFilterData) {
                    // Update the Tariff section
                    const tariffSection = $('#topLocationsTable tbody');
                    tariffSection.empty(); // Clear existing data

                    // Create a string to store the Tariff section text
                    let tariffText = '';
                    let locationIndex = 1; // Initialize locationIndex

                    for (const location in data.secondFilterData.topLocations) {
                        const bookingCount = data.secondFilterData.topLocations[location];

                        // Since we are looping through locations, there's no need to check for locationName and lastLocationName.
                        // Instead, you can use the 'location' variable directly.

                        tariffText += `${locationIndex}. ${location} - ${bookingCount} bookings<br>`;
                        locationIndex++; // Increment locationIndex
                    }

                    // Update the Tariff section with text
                    tariffSection.html(tariffText);


                    // Update the Assigned Vehicles section
                    const assignedVehiclesSection = $('#assignedVehiclesTable tbody');
                    assignedVehiclesSection.empty(); // Clear existing data

                    // Create a string to store the Assigned Vehicles section text
                    let vehiclesText = '';

                    data.secondFilterData.topVehicles.forEach((fleet, index) => {
                        const fleetText = `${index + 1}. ${fleet.unitName} - ${fleet.registrationNumber}`;
                        vehiclesText += `${fleetText} <br>`;
                    });

                    // Update the Assigned Vehicles section with text
                    assignedVehiclesSection.html(vehiclesText);
                }

                // Update the Individual Fleet Records section
                const individualFleetSection = $('#topVehiclesTable tbody');
                individualFleetSection.empty(); // Clear existing data

                // Create a string to store the Individual Fleet Records section text
                let individualFleetText = '';
                let totalMaintenanceCount = 0; 
                let totalAssignmentCount = 0; 

                if (data.topVehicleAssignments) {
                    totalMaintenanceCount = data.topVehicleAssignments.totalMaintenanceCount;
                    totalAssignmentCount = data.topVehicleAssignments.totalAssignmentCount;

                    data.topVehicleAssignments.topVehicleAssignments.forEach((assignment, index) => {
                        const maintenancePercentage = (assignment.maintenance_count / totalMaintenanceCount) * 100;
                        const assignmentPercentage = (assignment.assignment_count / totalAssignmentCount) * 100;

                        const fleetText = `
                            <tr>
                                <td>${index + 1}. ${assignment.unitName} - ${assignment.registrationNumber}</td>
                                <td>Maintenance Count: ${assignment.maintenance_count} 
                                    <div class="bar-container">
                                        <div class="statistics-bar" style="width: ${maintenancePercentage.toFixed(2)}%;"></div>
                                    </div>
                                </td>
                                <td>Assignment Count: ${assignment.assignment_count} 
                                    <div class="bar-container">
                                        <div class="statistics-bar" style="width: ${assignmentPercentage.toFixed(2)}%;"></div>
                                    </div>
                                </td>
                            </tr>`;

                        individualFleetText += fleetText;
                    });
                } else {
                    // Display a message or handle the absence of data
                    individualFleetText = '<tr><td colspan="3">No data available</td></tr>';
                }

                // Update the Individual Fleet Records section with text
                individualFleetSection.html(individualFleetText);


                // Update the Individual Fleet Records section with text
                individualFleetSection.html(individualFleetText);

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

                const maxRating = 5; // Define the maximum rating value
                const normalizedRating = Math.min(data.ratingAverage, maxRating); // Ensure rating is not higher than the maximum
                $('#ratingAverageValue').text(data.ratingAverage); // Update the rating average value
                const ratingWidth = (normalizedRating / maxRating) * 100;
                $('#ratingBar').css('width', ratingWidth + '%');
               
                globalData = data;
            }
        },
        error: function (error) {
            console.error('Failed to fetch data:', error);
        }
    });
}

document.getElementById('generatePdfButton').addEventListener('click', function () {
    if (globalData) {
        // Convert data to a JSON string
        const dataJson = JSON.stringify(globalData);

        // Encode the data as a URL parameter
        const dataParam = encodeURIComponent(dataJson);

        // Construct the URL with the data parameter
        const pdfUrl = `/generate-pdf?data=${dataParam}`; // Updated URL

        // Trigger the download by opening a new window or tab
        window.open(pdfUrl, '_blank');
    } else {
        console.error('Data not available for PDF generation');
    }
});




// Click event handler for the first filter buttons
$('.filter-nav a').click(function (e) {
    e.preventDefault();
    const filter = $(this).data('filter');
    updateSections(filter);

    // Remove the 'active' class from all first filter buttons
    $('.filter-nav a').removeClass('active');

    // Add the 'active' class to the clicked button
    $(this).addClass('active');
});

// Initial update for the first filter group
const initialFilter = '1M';
updateSections(initialFilter);

// Function to refresh data for the first filter
function refreshFirstFilterData() {
    const activeFilter = $('.filter-nav a.active').data('filter');
    updateSections(activeFilter);
}

// Refresh the data every X milliseconds for the first filter
setInterval(refreshFirstFilterData, 30000);
</script>
@endsection
