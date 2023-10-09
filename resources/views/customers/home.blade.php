@extends('layouts.index')

@section('content')
<style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        font-family: Arial, sans-serif;
    }

    #photoCarousel {
        position: relative;
        background: lightblue;
        background-size: cover;
        min-height: 100vh;       
    }

    .glass-search-box {
        background: rgba(255, 255, 255, 0.425);
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    h2 {
        color: #fff;
        font-size: 24px;
        margin-bottom: 20px;
    }

    form {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    label {
        color: #fff;
        margin-bottom: 8px;
    }

    input[type="date"] {
        padding: 10px;
        margin-bottom: 16px;
        border: none;
        border-radius: 5px;
    }

    button {
        background-color: #007BFF;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #0056b3;
    }
</style>

<div id="photoCarousel" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#photoCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#photoCarousel" data-slide-to="1"></li>
        <li data-target="#photoCarousel" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="{{ asset('images/carousel1.jpg') }}" class="d-block w-100" alt="Photo 1">
        </div>
        <div class="carousel-item">
            <img src="{{ asset('images/carousel2.jpg') }}" class="d-block w-100" alt="Photo 2">
        </div>
        <div class="carousel-item">
            <img src="{{ asset('images/2023grandia.jpg') }}" class="d-block w-100" alt="Photo 3">
        </div>
    </div>
    <a class="carousel-control-prev" href="#photoCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#photoCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>

    <div class="glass-search-box" style="border:3px solid white;">
        <h2 style="font-weight: 700;color:black;font-size:35px;font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;">Travel With Us</h2>
        <hr>
        <form id="searchForm" action="/selectvehicles" method="GET">
            <div class="row">
                <div class="col-md-6">
                    <label for="startDate" style="color:black"><strong>Start Date:</strong></label>
                    <input type="date" id="startDate" name="startDate" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                </div>
                <div class="col-md-6">
                    <label for="endDate" style="color: black"><strong>End Date:</strong></label>
                    <input type="date" id="endDate" name="endDate" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                </div>
                <div class="col-md-12">
                    <button type="submit">Search</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="container1" style="padding: 20px;background:none;box-shadow:none;width:100%">


</div>
@endsection

@section('scripts')
<script>

  document.getElementById('startDate').addEventListener('change', function() {
      var startDate = new Date(this.value);
      var endDateInput = document.getElementById('endDate');
      if (startDate) {
          endDateInput.min = startDate.toISOString().split('T')[0];
      }
  });

  document.getElementById('endDate').addEventListener('change', function() {
      var endDate = new Date(this.value);
      var startDateInput = document.getElementById('startDate');
      if (endDate) {
          startDateInput.max = endDate.toISOString().split('T')[0];
      }
  });


  document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('searchButton').addEventListener('click', function() {
        alert('Search button clicked');
        var startDate = document.getElementById('startDate').value;
        var endDate = document.getElementById('endDate').value;

        // Check if both start and end dates are selected
        if (startDate && endDate) {
            // Redirect to the selectvehicles route with selected dates as query parameters
            window.location.href = '/selectvehicles?startDate=' + startDate + '&endDate=' + endDate;
        } else {
            // Handle the case where dates are not selected
            alert('Please select both start and end dates.');
        }
    });
});


</script>


@endsection
