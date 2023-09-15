<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TS Tours</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">

    <style>
        /* Reset some default styles */
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            background: rgb(234,235,238);
            background: radial-gradient(circle, rgba(234,235,238,1) 0%, rgba(226,228,231,1) 23%, rgba(211,211,224,1) 50%, rgba(221,221,232,1) 79%, rgba(205,207,209,1) 100%);
        }

        h1 {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
            margin-bottom: 10px;
            margin-top: 10px;
            color: midnightblue;
            font-weight: 700;
        }

        p {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            color: #111111;
            text-align: center;
            line-height: 1.5;
            font-weight: 500;
        }

        /* Basic styling for the header */
        header {
            font-family: 'Montserrat', sans-serif;
            background-color: #333;
            color: midnightblue;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: rgba(255,255,255, .6);
        }

        /* Hollow Buttons */
        .gbtnHollow  {
     background:  transparent;
     height:  38px;
     line-height:  20px;
     border:  2px solid midnightblue;
     display:  inline-block;
     float:  none;
     text-align:  center;
     width:  120px;
     padding:  0px!important;
     font-size:  14px;
     color:  midnightblue;
    }

        .gbtnHollow:hover  {
     color:  white;
     background:  #181973;
     opacity: 100%;
    }

    /* Responsive styles */
    @media screen and (max-width: 768px) {
            header {
                flex-direction: column;
                text-align: center;
            }

            .logo {
                margin-bottom: 10px;
            }

            nav {
                text-align: center;
            }

            nav a {
                display: block;
                margin: 10px 0;
            }

            .container {
                display: block;
                margin: 10px 0;
            }

            .gbtnHollow {
                display: block;
                margin: 10px 0;
            }
        }

        /* Logo style */
        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            width: 50px; /* Adjust the width as needed */
            height: auto;
            margin-right: 10px;
        }

        .logo h1 {
            font-size: 24px;
            font-weight: bold;
        }

        /* Navigation bar */
        nav {
            text-align: right;
        }

        nav a {
            color: midnightblue;
            text-decoration: none;
            margin-right: 20px;
        }

        /* Main content container */
        .container {
            max-width: 98%;
            margin: 0 auto;
            padding: 10px;
            background-color: rgba(255,255,255, .6);
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Image Gallery */
        .image-gallery {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .image-card {
            position: relative;
            margin: 10px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s, filter 0.3s, box-shadow 0.3s; /* Added box-shadow transition */
        }

        .image-card:hover {
            transform: scale(1.2); /* Make images pop above by 20% */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2); /* Shadow effect on hover */
        }

        .image-card img {
            width: 350px;
            height: 275px; /* Fixed height and width for images */
            object-fit: cover; /* Maintain aspect ratio */
            filter: brightness(100%); /* Adjusted initial opacity effect */
            transition: filter 0.3s;
        }

        .image-card:hover img {
            filter: brightness(80%); /* Adjusted opacity on hover */
        }

        .image-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: linear-gradient(rgba(255, 255, 255, 0.4), rgba(0, 0, 0, 0.6)); /* Adjusted gradient effect */
            padding: 10px;
            border-radius: 5px;
            display: none;
            color: white; /* Text color */
        }

        .image-card:hover .image-text {
            display: block;
        }

         /* Add a class to trigger the fade-out transition */
.page-leave {
    opacity: 0;
    transition: opacity 0.3s;
}

/* Add a class to trigger the fade-in transition */
.page-enter {
    opacity: 1;
    transition: opacity 0.3s;
}
    </style>
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

    <br>
    <div class="container">
        <h1>ABOUT US</h1>
        <p>At TS Tours, we take pride in providing unmatched mobility solutions, combining top-quality vehicles, exceptional customer service, and a passion for delivering unforgettable travel experiences to every customer.</p>
    </div>
    <br>

    <!-- Image Gallery -->
    <div class="container">
        <h1>Featured Collection</h1>
        <div class="image-gallery">
            <div class="image-card">
                <img src="images/2023grandia.jpg" alt="Image 1">
                <div class="image-text">Grandia 2023</div>
            </div>
            <div class="image-card">
                <img src="images/2023grandia.jpg" alt="Image 2">
                <div class="image-text">Toyota</div>
            </div>
            <!-- Add more image cards here -->
            <div class="image-card">
                <img src="images/2023grandia.jpg" alt="Image 3">
                <div class="image-text">Lexus</div>
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

</body>
</html>
