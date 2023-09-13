@extends('layouts.app') 

@section('content')
<div class="container">
  <div class="row">
    @foreach($vehicles as $v)
    <div class="col-md-3">
      <div class="card" style="width: 18rem;">
        <img class="card-img-top" src="..." alt="Card image cap">
        <div class="card-body">
          <h5 class="card-title">{{ $v -> unitName}}</h5>
          <p class="card-text">Pax: {{ $v -> pax}}</p>
          <a href="{{ route('bookvehicle', ['vehicle' => $v -> unitID]) }}" class="btn btn-primary">Read More</a>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endsection