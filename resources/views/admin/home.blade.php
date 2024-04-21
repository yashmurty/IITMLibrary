@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome to Admin Dashboard</div>
                <div class="panel-body">
                    Approve requests in <a href="">Admin Request Status</a>
                    <br><br>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel-heading">BRF Management</div>

                            <a class="col-sm-12 col-md-4" href="{{ URL::route('adminrequeststatus') }}">
                            <div style="background-color:#DDD; padding: 15px;">
                                Admin Request Status
                            </div>
                            </a>

                            <a class="col-sm-12 col-md-4" href="{{ URL::route('adminrequeststatus-archived') }}">
                            <div style="background-color:#DDD; padding: 15px;">
                                Admin Request Status Archived
                            </div>
                            </a>

                            <a style="margin-top:0px;" class="col-sm-12 col-md-4" href="{{ URL::route('admin-brf-analytics') }}">
                            <div style="background-color:#DDD; padding: 15px;">
                                BRF Analytics
                            </div>
                            </a>

                            <a class="col-sm-12 col-md-4" href="{{ URL::route('admin-book-budget-year-list') }}">
                            <div style="background-color:#DDD; padding: 15px; margin-top:20px;">
                            <i class="fa fa-btn fa-money"></i>| Book Budget
                            </div>
                            </a>

                        </div>
                        <div class="col-sm-12">
                            <div class="panel-heading">Admin Management</div>

                            <a class="col-sm-12 col-md-4" href="{{ URL::route('admin-lacmembers') }}">
                            <div style="background-color:#DDD; padding: 15px; margin-top:0px;">
                                LAC Memebers
                            </div>
                            </a>

                            <a class="col-sm-12 col-md-4" href="{{ URL::route('admin-staffmembers') }}">
                            <div style="background-color:#DDD; padding: 15px; margin-top:0px;">
                                Admin Staff Members
                            </div>
                            </a>

                           

                        </div>
                        <div class="col-sm-12">
                            <div class="panel-heading">System Management</div>

                            <a style="margin-top:0px;" class="col-sm-12 col-md-4" href="{{ URL::route('admin-git-management') }}">
                            <div style="background-color:#DDD; padding: 15px;">
                                Git Management
                            </div>
                            </a>

                            <a style="margin-top:0px;" class="col-sm-12 col-md-4" href="{{ URL::route('admin-email-management') }}">
                            <div style="background-color:#DDD; padding: 15px;">
                                Email Management
                            </div>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
