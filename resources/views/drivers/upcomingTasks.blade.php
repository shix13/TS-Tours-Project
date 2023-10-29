@extends('layouts.driver-app')

@section('title')
    Upcoming Tasks
@endsection

@section('content')
<div class="container">
    @if(!$upcomingTasks->isEmpty())
    <h3 style="color: white">UPCOMING TASKS</h3>
        @foreach($upcomingTasks as $task)
            <div class="card">
                <div class="card-body">
                    @php
                        $startDateString = $task->booking->startDate;
                        $endDateString = $task->booking->endDate;
                        
                        $startDateCarbon = \Carbon\Carbon::parse($startDateString);
                        $startDate = $startDateCarbon->format('F, j, Y g:i A');

                        $endDateCarbon = \Carbon\Carbon::parse($endDateString);
                        $endDate = $endDateCarbon->format('F, j, Y');
                    @endphp
                    
                    <h5 class="card-title">{{ $task->booking->tariff->location }}, {{ $startDate }}</h5>
                    Pick-up at: {{ $task->booking->pickUp_Address}}<br/>
                    End date: {{ $endDate }}<br/>
                    @foreach($task->assignments as $assignment)
                        Vehicle: {{ $assignment->vehicle->unitName}} - {{ $assignment->vehicle->registrationNumber }} <br/>
                    @endforeach
                </div>
            </div>
        @endforeach
    @else
        <h3 style="color: white; text-align: center">You have no upcoming task</h3>
    @endif
</div>

<script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
@endsection