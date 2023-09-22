@extends('layouts.index')
@section('content')

<div class="container1 col-md-10" style="border:2px solid midnightblue">
        <h1 style="color: midnightblue"><strong>ABOUT US</strong></h1>
        <div >
            <p style="font-size:18px;font-weight:400;color:black">With <strong>TS Tours Services</strong>, we take pride in providing unmatched mobility solutions, combining top-quality vehicles, exceptional customer service, <br> and a passion for delivering unforgettable travel experiences to every customer.</p>
            <div >
                <br><p style="font-size:15px;font-weight:700;color:black">Featured Collection</p>
            <hr>
    
            <div class="image-gallery">
                <div class="image-card">
                    <img src="images/cbgrill1.jpg" alt="Image 1">
                    <div class="imgCaption" style="font-size:22px;font-weight:700;color:black">Mobility Solutions</div>
                </div>
                <div class="image-card">
                    <img src="images/readyts1.jpg" alt="Image 2">
                    <div class="imgCaption"  style="font-size:22px;font-weight:700;color:black">Top Quality Vehicles</div>
                </div>
                <!-- Add more image cards here -->
                <div class="image-card">
                    <img src="images/groupts1.jpg" alt="Image 3">
                    <div class="imgCaption" style="font-size:22px;font-weight:700;color:black">Exceptional Customer Service</div>
                </div>
            </div>
            <br>
        </div>
    </div>
 

    <br>
</div>
@endsection
