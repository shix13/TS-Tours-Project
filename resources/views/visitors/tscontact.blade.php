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
<div>
    <div class="row container1" >
        <div class="col-md-6" style="border:2px solid midnightblue">
            <h1><strong>Contact Us</strong></h1>
            <p style="font-size: 20px">We'd love to hear from you.<strong> Get in touch with us</strong>! </p>
                <br>

                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif

    
                <form action="{{ route('contact.send') }}" method="post">
                    @csrf <!-- CSRF protection -->
                    <div class="form-group">
                        <label for="name">Name of Sender:</label>
                        <input type="text" id="name" name="name" class="form-control" required placeholder="Your Name">
                    </div>
                
                    <div class="form-group">
                        <label for="email">Email Address:</label>
                        <input type="email" id="email" name="email" class="form-control" required placeholder="Your Email">
                    </div>
                
                    <div class="form-group">
                        <label for="phone">Phone Number:</label>
                        <input type="tel" id="phone" name="phone" class="form-control" placeholder="Your Phone">
                    </div>
                
                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea id="message" name="message" class="form-control" rows="4" required placeholder="Your Message"></textarea>
                    </div>
                
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Send</button>
                </form>
                
        </div>

        <div class="col-md-4 container1" style="display: inline-block">
            <div class="contact-info">
                <h3><strong>Contact Information</strong></h3>
                <p><i class="fa fa-phone"></i> Contact Number: +0995-132-0184</p>
                <p><i class="fa fa-map-marker"></i> Address: Motong, Negros Oriental</p>
                <p><i class="fa fa-envelope"></i> Email Address: tstours@gmail.com</p>
                <br>
            </div>

            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3937.1949945902056!2d123.2838163748622!3d9.315983590757229!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33ab6f122cf163b9%3A0xaedb715398294d68!2sTS%20Tours%20Services!5e0!3m2!1sen!2sph!4v1694332877199!5m2!1sen!2sph" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</div>





    <br>

   
</html-->
@endsection