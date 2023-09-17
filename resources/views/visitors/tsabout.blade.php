<!--!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TS Tours</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">

    <link rel="icon" href="{{ asset('images/TS TOURS.jpg') }}">
</head-->
<!--body>
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
@extends('layouts.custbar')
@section('content')
    <div class="container">
        <h1>ABOUT US</h1>
        <p>At TS Tours, we take pride in providing unmatched mobility solutions, combining top-quality vehicles, exceptional customer service, and a passion for delivering unforgettable travel experiences to every customer.</p>
    </div>
    <br>

    <!-- Image Gallery -->
    <div class="container">
        <h1>Featured Collection</h1>
        <br>

        <div class="image-gallery">
            <div class="image-card">
                <img src="images/cbgrill1.jpg" alt="Image 1">
                <div class="image-text">Fleet</div>
            </div>
            <div class="image-card">
                <img src="images/readyts1.jpg" alt="Image 2">
                <div class="image-text">Ready</div>
            </div>
            <!-- Add more image cards here -->
            <div class="image-card">
                <img src="images/groupts1.jpg" alt="Image 3">
                <div class="image-text">Team</div>
            </div>
        </div>

        <br>
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

<!--/body>
</html-->
@endsection
