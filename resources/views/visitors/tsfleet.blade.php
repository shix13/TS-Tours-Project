<!--DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TS Tours</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">

    <link rel="icon" href="{{ asset('images/TS TOURS.jpg') }}">
</head>
<body>
    <header>
        <div class="logo">
            <a href="/"><img src="{{ asset('images/TS Tours.jpg') }}" alt="TS logo"></a>
            <h1>TS Tours</h1>
        </div>
        <nav>
            <button class="gbtnHollow" data-href="aboutus">About Us</button>
            <button class="gbtnHollow" data-href="fleet">Vehicles</button>
            <button class="gbtnHollow" data-href="contactus">Contact Us</button>
            <button class="gbtnHollow" data-href="login">Login</button>
        </nav>
    </header>

    <br-->
@extends('layouts.index')
@section('content')
    <!-- Image Gallery -->
    <div class="container1">
        <h1>Vehicles</h1>
        <br>

        <div class="image-gallery">
            <div class="image-card">
                <img src="images/2017grandia.jpg" alt="Image 1">
                <div class="name-card">
                    <p><b>Grandia 2017</b></p>
                    <p>Bookings via TS Tours website.</p>
                </div>
            </div>
            <div class="image-card">
                <img src="images/2023grandia.jpg" alt="Image 2">
                <div class="name-card">
                    <p><b>Grandia 2023</b></p>
                    <p>Bookings via TS Tours website.</p>
                </div>
            </div>
            <!-- Add more image cards here -->
            <div class="image-card">
                <img src="images/benz1.jpg" alt="Image 3">
                <div class="name-card">
                    <p><b>Others</b></p>
                    <p>Booking via Contact for Special Occassions.</p>
                </div>
            </div>
        </div>

        <br>
        
    </div>

<br><br>

<!--/body>
</html-->
@endsection