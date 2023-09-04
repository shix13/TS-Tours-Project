@extends('layouts.empbar')

@section('title')
    TS | Edit Tariff
@endsection

@section('content')
<br><br>
<div class="container">
    <div class="row">
        <div class="col-md-12 offset-md-0">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title" style="color: red;">Edit Tariff</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('tariffs.update', $tariff->tariffID) }}">
                        @csrf
                        @method('PUT') <!-- Use PUT method for updates -->

                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" required value="{{ $tariff->location }}">
                            @error('location')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="rate_Per_Day">Rate Per Day</label>
                            <input type="number" name="rate_Per_Day" id="rate_Per_Day" class="form-control @error('rate_Per_Day') is-invalid @enderror" required value="{{ $tariff->rate_Per_Day }}">
                            @error('rate_Per_Day')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="rent_Per_Hour">Rent Per Hour</label>
                            <input type="number" name="rent_Per_Hour" id="rent_Per_Hour" class="form-control @error('rent_Per_Hour') is-invalid @enderror" required value="{{ $tariff->rent_Per_Hour }}">
                            @error('rent_Per_Hour')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update Tariff</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

@endsection
