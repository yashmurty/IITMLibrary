@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Request Status</div>

                <div class="panel-body">
                    
                    <table class="table"> 
                        <caption>Status of your requests submitted via Book Requisition Form</caption> 
                        <thead> 
                            <tr> 
                                <th>#</th> 
                                <th>Faculty</th> 
                                <th>Doctype</th> 
                                <th>Author</th> 
                                <th>Title</th> 
                                <th>LAC Status</th> 
                                <th>Librarian Status</th> 
                                <th>Remarks</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            @if(!($user_brfs == null))
                                @foreach ($user_brfs as $key => $user_brf) 
                                <tr> 
                                    <th scope="row">
                                        <!-- {{ $key + 1 }}  -->
                                        #{{ $user_brf->id }} </br>
                                        <span class="label label-default">{{ \Carbon\Carbon::parse($user_brf->created_at)->format('Y-m-d') }}</span>
                                    </th> 
                                    <td>{{ $user_brf->faculty }}</td> 
                                    <td>{{ $user_brf->doctype }}</td> 
                                    <td>{{ $user_brf->author }}</td> 
                                    <td>{{ $user_brf->title }}</td>

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
                                </tr> 
                                @endforeach
                            @else
                                You have not made any requests yet.
                            @endif
                                
                        </tbody> 
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
