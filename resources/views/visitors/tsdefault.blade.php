@extends('layouts.index')
@section('content')
 <style>
        /* CSS for setting the background image */
        body {
            background-image: url('{{ asset('/storage/images/bg.jpg') }}'); 
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }
    </style>
    <body>
        
    
 <div class="container1 text-center fade-in">
        <div class="card">
            <div class="card-header"></div>
            <div class="card-body" style="border:2px solid midnightblue">
                <h1><strong>Welcome to TS Tours Services</strong></h1>
                <p style="font-size:18px;font-weight:400;color:black">Welcome to our premier car rental service, where convenience, reliability, and a fleet of top-notch vehicles await to make your journey unforgettable!</p>
            </div>
        </div>
    </div>
    
    
    <br>
    
    <div class="container1 content-split">
        <div class="row">
            <div class="col-md-6">
                <h2 >OUR TEAM</h2>
                <p style="font-size:15px;font-weight:400;color:black">Our dedicated car rental service team is committed to delivering exceptional customer experiences, ensuring smooth rides and memorable journeys for all our valued clients.</p>
            </div>
            
            <div class="col-md-6" >
                <h2>Why Us?</h2>
                <p style="font-size:15px;font-weight:400;color:black">Seamless and stress-free experience, offering an extensive selection of vehicles, competitive rates, and personalized assistance to cater to all your travel needs</p>
            </div>
        </div>
    </div>
</body>
<div style="margin-bottom: 180px"></div>
@endsection
