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
        }

        .image-card img {
            width: 350px;
            height: 275px; /* Fixed height and width for images */
            object-fit: cover; /* Maintain aspect ratio */
        }

        .name-card {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.8);
            padding: 10px;
            text-align: left;
        }

        .name-card h2 {
            margin: 0;
            font-size: 18px;
            color: midnightblue;
        }

        .name-card p {
            margin: 5px 0;
            font-size: 14px;
            color: #111111;
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
    <h1>Contact Us</h1>
    <p>Feel free to get in touch with us. We'd love to hear from you!</p>

    <form action="process-form.php" method="post" class="d-flex flex-column align-items-center">
        <div class="form-group col-md-6">
            <label for="name">Name of Sender:</label>
            <input type="text" id="name" name="name" class="form-control" required placeholder="Your Name">
        </div>

        <div class="form-group col-md-6">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" class="form-control" required placeholder="Your Email">
        </div>

        <div class="form-group col-md-6">
            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" class="form-control" required placeholder="Your Phone">
        </div>

        <div class="form-group col-md-6">
            <label for="message">Message:</label>
            <textarea id="message" name="message" class="form-control" rows="4" required placeholder="Your Message"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <br>

    <div class="contact-info">
        <h2>Contact Information</h2>
        <p><i class="fa fa-phone"></i> Contact Number: +0995-132-0184</p>
        <p><i class="fa fa-map-marker"></i> Address: Motong, Negros Oriental</p>
        <p><i class="fa fa-envelope"></i> Email Address: tstours@gmail.com</p>
    </div>

    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3937.1949945902056!2d123.2838163748622!3d9.315983590757229!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33ab6f122cf163b9%3A0xaedb715398294d68!2sTS%20Tours%20Services!5e0!3m2!1sen!2sph!4v1694332877199!5m2!1sen!2sph" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        
</div>




    <br>

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
