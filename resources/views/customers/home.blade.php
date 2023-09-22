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
        top: 95%;
        left: 50%;
        transform: translate(-50%, -95%);
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
            <img src="{{ asset('/storage/images/carousel1.jpg') }}" class="d-block w-100" alt="Photo 1">
        </div>
        <div class="carousel-item">
            <img src="{{ asset('/storage/images/carousel2.jpg') }}" class="d-block w-100" alt="Photo 2">
        </div>
        <div class="carousel-item">
            <img src="{{ asset('/storage/images/carousel3.jpg') }}" class="d-block w-100" alt="Photo 3">
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
    
    <div class="glass-search-box">
        <h2 style="font-weight: 700;color:black;font-size:35px;font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">Travel With Us</h2>
        <form action="#">
            <div class="row">
                <div class="col-md-6">
                    <label for="startDate" style="color: rgb(50, 50, 50);"><strong>Start Date:</strong></label>
                    <input type="date" id="startDate" name="startDate" required>
                </div>
                <div class="col-md-6">
                    <label for="endDate"  style="color: rgb(50, 50, 50);"><strong>End Date:</strong></label>
                    <input type="date" id="endDate" name="endDate" required>
                </div>
                <div class="col-md-12">
                    <button type="submit">Search</button>
                </div>
            </div>
        </form>
    </div>
    </div>

    <div class="container1" style="padding: 20px;background:none;box-shadow:none;width:100%">
  
  
        <!-- Add search and filter section on the right side -->
        <div class="row">
        <!-- Browse Vehicle text on the left -->
        <div class="col-md-6">
          <h1 style="text-align: left; padding-left: 50px; font-size: 30px; font-weight: 700">Browse Vehicle</h1>
        </div>
      
        <!-- Search bar on the right -->
        <div class="col-md-6">
          <div class="input-group mb-3">
            <input type="text" class="form-control" style="background: white;border-radius:10px" placeholder="Search..." aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
              <button style="background: midnightblue;padding:10px;color:white;border-radius:5px;"> <i class="fas fa-search"></i> Search</button>
            </div>
          </div>
        </div>
      </div>
      
        
      
      <hr>
      <div class="row">
        @foreach($vehicles as $v)
        <div class="col-md-3">
            <div class="card" style="width: 18rem;border-radius:10px;height:380px">
                <div style="max-height: 250px; overflow: hidden;">
                    <img class="card-img-top" src="{{ asset('storage/' . $v->pic) }}" alt="Card image cap" style="width: 100%;" height="auto">
                </div>
                <div class="card-content" style="height: 220px; display: flex; flex-direction: column; justify-content: space-between;">
                  <br>
                    <h5 class="card-title" style="font-size: 30px;"><strong>{{ $v->unitName }}</strong></h5>
                    <div class="specification">
                        @if ($v->specification)
                            <p style="font-weight: 400; font-size: 15px;padding:8px">{{ strlen($v->specification) > 100 ? substr($v->specification, 0, 90) . '..' : $v->specification}}</p>
                        @else
                            <p style="font-weight: 400; font-size: 15px;">--No Description--</p>
                        @endif
                    </div>
                    <div style="justify-content: center">
                    <a href="{{ route('bookvehicle', ['vehicle' => $v->unitID]) }}" class="btn btn-primary" style="align-self: flex-end;">Book Now</a>
                </div>
              </div>
            </div>
        </div>
        @endforeach
      </div>
</div>

@endsection
