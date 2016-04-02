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
                                    <th scope="row">{{ $key + 1 }}</th> 
                                    <td>{{ $user_brf->doctype }}</td> 
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
