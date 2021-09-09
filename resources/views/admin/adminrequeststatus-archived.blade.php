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
                              <li><a href="{{ URL::route('adminrequeststatus') }}/archived/approved/2021-2022">2021-2022</a></li>
                              <li><a href="{{ URL::route('adminrequeststatus') }}/archived/approved/2020-2021">2020-2021</a></li>
                              <li><a href="{{ URL::route('adminrequeststatus') }}/archived/approved/2019-2020">2019-2020</a></li>
                              <li><a href="{{ URL::route('adminrequeststatus') }}/archived/approved/2018-2019">2018-2019</a></li>
                              <li><a href="{{ URL::route('adminrequeststatus') }}/archived/approved/2017-2018">2017-2018</a></li>
                              <li><a href="{{ URL::route('adminrequeststatus') }}/archived/approved/2016-2017">2016-2017</a></li>
                            </ul>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                          <h4>
                            Denied Requests
                          </h4>
                          <ul class="">
                            <li><a href="{{ URL::route('adminrequeststatus') }}/archived/denied/2021-2022">2021-2022</a></li>
                            <li><a href="{{ URL::route('adminrequeststatus') }}/archived/denied/2020-2021">2020-2021</a></li>
                            <li><a href="{{ URL::route('adminrequeststatus') }}/archived/denied/2019-2020">2019-2020</a></li>
                            <li><a href="{{ URL::route('adminrequeststatus') }}/archived/denied/2018-2019">2018-2019</a></li>
                            <li><a href="{{ URL::route('adminrequeststatus') }}/archived/denied/2017-2018">2017-2018</a></li>
                            <li><a href="{{ URL::route('adminrequeststatus') }}/archived/denied/2016-2017">2016-2017</a></li>
                          </ul>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
