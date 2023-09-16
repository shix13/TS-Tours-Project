<!--!DOCTYPE html>
<html lang="en"-->
<!--head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TS Tours</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">

    <link href="{{ asset('css/customer.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/TS TOURS.jpg') }}">
</head-->

<!--body-->
    <!--header>
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
    </header-->

    <!--br-->
@extends('layouts.custbar')
@section('content')
    <div class="container">
        <h1>Welcome to TS Tours Services</h1>
        <p>Welcome to our premier car rental service, where convenience, reliability, and a fleet of top-notch vehicles await to make your journey unforgettable!</p>
    </div>

    <br>
    <div class="container">
        <div class="row">
            <div class="col-sm">
                <h1>Our Team</h1>
                <p>Our dedicated car rental service team is committed to delivering exceptional customer experiences, ensuring smooth rides and memorable journeys for all our valued clients.</p>
            </div>

            <div class="col-sm">
                <h1>Why Us?</h1>
                <p>Seamless and stress-free experience, offering an extensive selection of vehicles, competitive rates, and personalized assistance to cater to all your travel needs</p>
            </div>
        </div>
    </div>

    <script>
    // Add click event listeners to navigation buttons
    const buttons = document.querySelectorAll('.gbtnHollow');

    buttons.forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault(); // Prevent default link behavior

            // Add a class to trigger the transition
            document.body.classList.add('page-leave');

            // Delay the actual page redirection by a short time
            setTimeout(() => {
                window.location.href = event.target.getAttribute('data-href');
            }, 300); // Adjust the delay time (in milliseconds) as needed
        });
    });
    </script>

<!--/body-->
<!--/html-->
@endsection
