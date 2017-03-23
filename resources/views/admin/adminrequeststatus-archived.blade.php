@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Admin Request Status Archived</div>

                <div class="panel-body">

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
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>{{ $user_brf->faculty }}</td>
                                    <td>{{ $user_brf->iitm_dept_code }}</td>
                                    <td>{{ $user_brf->doctype }}</td>
                                    <td>{{ $user_brf->author }}</td>
                                    <td>{{ $user_brf->title }}</td>
                                    @if( $user_brf->lac_status == null)
                                        <td><i class="fa fa-clock-o"></i></td>
                                    @elseif( $user_brf->lac_status == "approved" )
                                        <td><i class="fa fa-check-circle" style="color:green"></i></td>
                                    @else
                                        <td><i class="fa fa-times-circle" style="color:red"></i></td>
                                    @endif

                                    @if( $user_brf->librarian_status == null)
                                        <td><i class="fa fa-clock-o"></i></td>
                                    @elseif( $user_brf->librarian_status == "approved" )
                                        <td><i class="fa fa-check-circle" style="color:green;"></i></td>
                                    @else
                                        <td><i class="fa fa-times-circle" style="color:red"></i></td>
                                    @endif

                                    <td>{{ $user_brf->remarks }}</td>
                                    <td><a href="{{ url('admin/requeststatus/brf/') }}/{{ $user_brf->id }}" class="btn btn-primary">View</a></td>
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
