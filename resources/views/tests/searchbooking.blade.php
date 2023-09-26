@extends('layouts.index')

@section('content')

<div class="container1">
    <div class="col">
        <h1 style="text-align: center;font-size: 30px; font-weight: 700">Enter Booking ID</h1>
    </div>

    <form method="POST" action="{{ route('searchbooking') }}">
        @csrf

    <div class="form-group text-center">
        <input name="reserveID" type="text" class="form-control" placeholder="Search booking" style="background: white;padding:20px;border:2px solid midnightblue;">
        <button type="submit" class="btn btn-primary"  style="margin-top: 10px">Search</button>
    </div>
    </form>
</div>
<script>

</script>
@endsection