@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <p>
                    You are logged in! Visit the <a href="{{ URL::route('bookrequisitionform') }}">Book Requisition Form</a>
                    </p>
                    <p>
                        View <a href="#">Request Status</a> of previous requests.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
