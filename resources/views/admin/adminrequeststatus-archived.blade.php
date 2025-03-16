@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome to Admin Dashboard</div>
                <div class="panel-body">
                    Approve requests in <a href="{{ URL::route('adminrequeststatus') }}">Admin Request Status</a>

                    <br><br>

                    <div class="row">
                        <div class="col-sm-12">
                            <h4>
                                Approved Requests
                            </h4>
                            <ul class="">
                                @for ($year = 2025; $year >= 2015; $year--)
                                <li><a href="{{ URL::route('adminrequeststatus') }}/archived/approved/{{ $year }}-{{ $year + 1 }}">{{ $year }}-{{ $year + 1 }}</a></li>
                                @endfor
                            </ul>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <h4>
                                Denied Requests
                            </h4>
                            <ul class="">
                                @for ($year = 2025; $year >= 2015; $year--)
                                <li><a href="{{ URL::route('adminrequeststatus') }}/archived/denied/{{ $year }}-{{ $year + 1 }}">{{ $year }}-{{ $year + 1 }}</a></li>
                                @endfor
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection