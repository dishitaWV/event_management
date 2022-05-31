@extends('layout')

@section('content')
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 justify-content-center">
        <div>
            <h2>Event Management</h2>
        </div>
        <div class="row" style="margin-bottom: 10px">
            <div class="col-lg-6">
                <a class="btn btn-success" href="{{ route('events.create') }}"> Create Event</a>
            </div>
            <div class="input-group col-lg-6">
                <input type="text" class="form-control" placeholder="Search for..." name="search_dates" id="search_dates" value="">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" id="searchBtn">Go!</button>
                </span>
            </div>
        </div>
        <div class="row" style="margin-bottom: 10px">
            <div class="col-lg-8"></div>
            <div class="col-lg-4"><button class="btn btn-info pull-right" id="download_pdf_btn"> Download PDF</button></div>
        </div>
        @if ($message = \Illuminate\Support\Facades\Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
        @endif
        <table class="table table-bordered" id="event_table">
            <thead>
            <tr>
                <th>No.</th>
                <th>Event Title</th>
                <th>Event Type</th>
                <th>Event Date</th>
                <th>Action</th>
            </tr>
            </thead>

            <tbody>
            @if(count($events)==0)
                <tr>
                    <td colspan="5" style="text-align: center">There is no records.</td>
                </tr>
            @else
            <?php $i = 1; ?>
            @foreach ($events as $event)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $event->title }}</td>
                    <td>{{ $event->type }}</td>
                    <td>{{ $event->event_date }}</td>
                    <td>
                        <form action="{{ route('events.destroy',$event->id) }}" method="Post" id="deleteEventForm">
                            @csrf
                            @method('DELETE')
                            <a class="btn btn-primary" href="{{ route('events.edit',$event->id) }}">Edit</a>
                            <button type="button" class="btn btn-danger" id="deleteEvent">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php $i++; ?>
            @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
@endsection

