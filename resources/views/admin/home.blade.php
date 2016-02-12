@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome to Admin Dashboard</div>
                <div class="panel-body">
                    Approve requests in <a href="{{ URL::route('adminrequeststatus') }}">Admin Request Status</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
