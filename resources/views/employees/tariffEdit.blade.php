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
                    <h4 class="card-title" style="color: red;"><i class="fas fa-edit"></i> Edit Tariff</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('tariffs.update', $tariff->tariffID) }}">
                        @csrf
                        @method('PUT') <!-- Use PUT method for updates -->

                        <div class="form-group">
                            <label for="location" style="color: black"><i class="fas fa-map-marker-alt"></i>  Location</label>
                            <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" required value="{{ $tariff->location }}">
                            @error('location')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="row">
                        <div class="form-group col-md-3 pr-3" >
                            <label for="rate_Per_Day" style="color: black"><i class="fas fa-sun"></i> Rate Per Day</label>
                            <input type="number" name="rate_Per_Day" id="rate_Per_Day" class="form-control @error('rate_Per_Day') is-invalid @enderror" required value="{{ $tariff->rate_Per_Day }}" min="1">
                            @error('rate_Per_Day')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3 pr-3">
                            <label for="rentPerDayHrs" style="color: black"><i class="fas fa-clock"></i> Number of Hrs (of Rent per Day)</label>
                            <input type="number" name="rentPerDayHrs" id="rentPerDayHrs" class="form-control @error('rentPerDayHrs') is-invalid @enderror" required value="{{ $tariff->rentPerDayHrs }}" min="1">
                            @error('rentPerDayHrs')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3 pr-3">
                            <label for="rent_Per_Hour" style="color: black"><i class="fas fa-clock"></i> Additional Cost of Rent Per Hour</label>
                            <input type="number" name="rent_Per_Hour" id="rent_Per_Hour" class="form-control @error('rent_Per_Hour') is-invalid @enderror"  value="{{ $tariff->rent_Per_Hour }}" min="0">
                            @error('rent_Per_Hour')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3 pr-3">
                            <label for="do_pu_rate" style="color: black"><i class="fas fa-car"></i>  Pick-Up/Drop-Off Rate</label>
                            <input type="number" name="do_pu_rate" id="do_pu_rate" class="form-control @error('do_pu_rate') is-invalid @enderror"  value="{{ $tariff->do_pu }}" min="0">
                            @error('do_pu_rate')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                    </div>

                        <div class="form-group">
                            <label for="note" style="color: black"><i class="fas fa-sticky-note"></i> Note</label>
                            <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror">{{ $tariff->note }}</textarea>
                            @error('note')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Update Tariff</button>
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
