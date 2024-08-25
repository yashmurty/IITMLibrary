@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Admin Request Status</div>

                <div class="panel-body">
                    @include('admin.requeststatus-excel-export')

                    @include('staff-approver.requeststatus-table')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection