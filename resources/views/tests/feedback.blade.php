@extends('layouts.index')

@section('content')
<div class="container" style="background: white; border-radius: 20px;">
    <br>
    <h1 style="font-weight: 700; font-size: 45px;"> <i class="fas fa-comments"></i> Add Feedback</h1>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="post" action="{{ route('feedback.store') }}">
        @csrf
        <div class="form-group" style="font-size: 20px" hidden>
            <label  for="rentID">
                <i class="fas fa-barcode"></i> RentID:
            </label>
            <input type="text" id="rentID" name="rentID" value="{{ $feedback->rentID }}" class="form-control">
        </div>
        <div class="form-group" style="font-size: 20px">
            <label  for="TrackingID">
                <i class="fas fa-barcode"></i> Tracking ID:
            </label>
            <input type="text" id="TrackingID" name="TrackingID" value="{{ $feedback->reserveID }}" class="form-control">
        </div>
        <div class="form-group" style="font-size: 20px">
            <label for="feedback_Message">
                <i class="fas fa-comments"></i>  Feedback Message:
            </label>
            <textarea  id="feedback_Message" name="feedback_Message" class="form-control">{{ $feedback->feedback_Message }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> Send
        </button>
    </form>
</div>
@endsection
