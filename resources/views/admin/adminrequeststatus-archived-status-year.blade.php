@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Admin Request Status Archived</div>

                <div class="panel-body">
                    Showing <strong>{{ $archived_status }}</strong> data for <strong>1st April, {{ $year_from }}</strong> to <strong>31st March, {{ $year_until }} </strong>
                    <!-- Yearwise button -->
                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            View Year-wise <span class="caret"></span>
                        </button>

                        <ul class="dropdown-menu">
                            @for ($year = 2024; $year >= 2016; $year--)
                            <li><a href="{{ URL::route('adminrequeststatus') }}/archived/{{ $archived_status }}/{{ $year }}-{{ $year + 1 }}">{{ $year }}-{{ $year + 1 }}</a></li>
                            @endfor
                        </ul>

                    </div>
                    <a href="{{ URL::route('adminrequeststatus-archived') }}" class="btn btn-default">Go Back to Archived Requests</a>
                    <p>
                        Export the <strong>{{ $archived_status }}</strong> requests
                        <a href="{{ URL::route('adminrequeststatus') }}/exporttoexcel/{{ $archived_status }}/{{ $year_from }}-{{ $year_until }}" class="btn btn-primary">Export to Excel</a>
                    </p>
                    <p style="padding:10px;" class="bg-primary">BRF Requests in this period :
                        <strong>
                            @if(!($admin_user_brfs == null))
                            {{ count($admin_user_brfs) }}
                            @else
                            0
                            @endif
                        </strong>
                    </p>

                    <table class="table">
                        <caption>Status of requests under you submitted via Book Requisition Form</caption>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Faculty</th>
                                <th>Department</th>
                                <th>Doctype</th>
                                <th>Author</th>
                                <th>Title</th>
                                <th>Updated At</th>
                                <th>LAC Status</th>
                                <th>Librarian Status</th>
                                <th>Remarks</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!($admin_user_brfs == null))
                            @foreach ($admin_user_brfs as $key => $user_brf)
                            @if( $user_brf->download_status == null)
                            <tr>
                                @elseif( $user_brf->download_status == "downloaded" )
                            <tr class="success">
                                @endif
                                <th scope="row">
                                    <!-- {{ $key + 1 }}  -->
                                    #{{ $user_brf->id }} </br>
                                    <span class="label label-default">{{ \Carbon\Carbon::parse($user_brf->created_at)->format('Y-m-d') }}</span>
                                </th>
                                <td>{{ $user_brf->faculty }}</td>
                                <td>{{ $user_brf->iitm_dept_code }}</td>
                                <td>{{ $user_brf->doctype }}</td>
                                <td>{{ $user_brf->author }}</td>
                                <td>{{ $user_brf->title }}</td>
                                <td>{{ $user_brf->updated_at }}</td>

                                <td>
                                    @if( $user_brf->lac_status == null)
                                    <i class="fa fa-clock-o"></i>
                                    @elseif( $user_brf->lac_status == "approved" )
                                    <i class="fa fa-check-circle" style="color:green"></i>
                                    @else
                                    <i class="fa fa-times-circle" style="color:red"></i>
                                    @endif

                                    @if($user_brf->lac_status_date)
                                    <span class="label label-default">{{ \Carbon\Carbon::parse($user_brf->lac_status_date)->format('Y-m-d') }}</span>
                                    @endif
                                </td>

                                <td>
                                    @if( $user_brf->librarian_status == null)
                                    <i class="fa fa-clock-o"></i>
                                    @elseif( $user_brf->librarian_status == "approved" )
                                    <i class="fa fa-check-circle" style="color:green;"></i>
                                    @else
                                    <i class="fa fa-times-circle" style="color:red"></i>
                                    @endif

                                    @if($user_brf->librarian_status_date)
                                    <span class="label label-default">{{ \Carbon\Carbon::parse($user_brf->librarian_status_date)->format('Y-m-d') }}</span>
                                    @endif
                                </td>

                                <td>{{ $user_brf->remarks }}</td>
                                <td><a href="{{ url('staff-approver/requeststatus/brf/') }}/{{ $user_brf->id }}" class="btn btn-primary">View</a></td>
                            </tr>
                            @endforeach
                            @else
                            You don't have any approval requests yet.
                            @endif

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection