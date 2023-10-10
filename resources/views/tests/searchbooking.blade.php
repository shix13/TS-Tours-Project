@extends('layouts.index')

@section('content')

<div class="container1">
    <img src="{{ asset('images/search-booking.png') }}" style="border-radius: 10px;">
    <hr>
    <form method="POST" action="{{ route('searchbooking') }}">
        @csrf

    <div class="col form-group text-center">
        <input name="reserveID" type="text" class="form-control" placeholder="Search booking" style="background: white;padding:20px;border:2px solid midnightblue;">
        <button type="submit" class="btn btn-primary"  style="margin-top: 10px">Search</button>
    </div>
    </form>
</div>
<script>

</script>
@endsection