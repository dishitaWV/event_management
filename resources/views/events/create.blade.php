@extends('layout')

@section('content')
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 justify-content-center">
        <div class="pull-left mb-2">
            <h2>Add Event</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('events.index') }}"> Back</a>
        </div>

    @if(session('status'))
        <div class="alert alert-success mb-1 mt-1">
            {{ session('status') }}
        </div>
    @endif
    <form action="{{ route('events.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-xs-8 col-sm-8 col-md-8 container justify-content-center">
                <div class="form-group">
                    <strong>Title:</strong>
                    <input type="text" name="title" class="form-control" placeholder="Event Title" value="{{ old('title') }}">
                    @error('title')
                    <div style="color: red">{{ $message }}</div>
                    @enderror
                </div>
    {{--        </div>--}}
    {{--        <div class="col-xs-8 col-sm-8 col-md-8">--}}
                <div class="form-group">
                    <strong>Type:</strong>
                    <input type="text" name="type" class="form-control" placeholder="Event Type" value="{{ old('type') }}">
                    @error('type')
                    <div style="color: red">{{ $message }}</div>
                    @enderror
                </div>
    {{--        </div>--}}
    {{--        <div class="col-xs-8 col-sm-8 col-md-8">--}}
                <div class="form-group">
                    <strong>Date:</strong>
                    <input type="text" class="form-control custom_date_picker" id="event_date" name="event_date" placeholder="yyyy-mm-dd" value="{{ old('event_date') }}">
                    @error('event_date')
                    <div style="color: red">{{ $message }}</div>
                    @enderror
                </div>
    {{--        </div>--}}
            <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>

    </div>
</div>
@endsection




