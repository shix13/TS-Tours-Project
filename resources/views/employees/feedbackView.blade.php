@extends('layouts.empbar')

@section('title')
    TS | Customer Feedbacks
@endsection

@section('content')
<br><br>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Feedbacks</h4>
            </div>
            <div class="card-body">
                @if($feedbacks->isEmpty())
                    <p>No feedbacks available.</p>
                @else
                    <div class="table-responsive">
                        <table id="table" class="table table-hover table-striped">
                            <thead class="text-primary font-montserrat ">
                                <tr>
                                    <th class="bold-text"> <strong>Index</strong></th>
                                    <th class="bold-text"><strong>Booking Number</strong></th>
                                    <th class="bold-text"><strong>Rating</strong></th>
                                    <th class="bold-text"><strong>Feedback Message</strong></th>
                                    <th class="bold-text"><strong>Created At</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($feedbacks as $index => $feedback)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $feedback->rental->reserveID }}</td>
                                        <td class="text-center" ><strong>{{ $feedback->rating }}</strong></td>
                                        <td>{{ $feedback->feedback_Message }}</td>
                                        <td>{{ $feedback->created_at->format('F j, Y  | H:i a') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Add pagination links -->
                    {{ $feedbacks->links() }}
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
