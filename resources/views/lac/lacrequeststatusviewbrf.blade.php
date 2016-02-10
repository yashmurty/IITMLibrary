@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">LAC - View BRF Request</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('lac/requeststatus/brf') }}">
                        {!! csrf_field() !!}
                        
                        @if(!($lac_user_brf == null))
                            <h4 class="text-center">{{ $lac_user_brf->title }}</h4>
                            <hr>
                            <div class="col-md-8 col-md-offset-2">
                                <p><strong>Document Type :</strong> {{ $lac_user_brf->doctype }}</p>
                                <p><strong>Author :</strong> {{ $lac_user_brf->author }}</p>
                                <p><strong>Title :</strong> {{ $lac_user_brf->title }}</p>
                                <p><strong>Publisher :</strong> {{ $lac_user_brf->publisher }}</p>
                                <p><strong>Agency :</strong> {{ $lac_user_brf->agency }}</p>
                                <p><strong>ISBN :</strong> {{ $lac_user_brf->isbn  }}</p>
                                <p><strong>Volume :</strong> {{ $lac_user_brf->volumne }}</p>
                                <p><strong>Price :</strong> {{ $lac_user_brf->price }}</p>
                                <p><strong>Section Catalogue :</strong> {{ $lac_user_brf->sectioncatalogue }}</p>
                                <p><strong>Number of Copies :</strong> {{ $lac_user_brf->numberofcopies }}</p>
                                <p><strong>LAC Status :</strong> {{ $lac_user_brf->lac_status }}</p>
                                <p><strong>Librarian Status :</strong> {{ $lac_user_brf->librarian_status }}</p>
                                <p><strong>Remarks :</strong> {{ $lac_user_brf->remarks }}</p>
                                <hr>
                                @if( $lac_user_brf->librarian_status == "approved" )
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-danger btn-lg btn-block disabled">Deny</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-success btn-lg btn-block disabled">Approve</button>
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="submit" value="deny" class="btn btn-danger btn-lg btn-block">Deny</button>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="submit" name="button" value="approved" class="btn btn-success btn-lg btn-block">Approve</input>
                                    </div>
                                </div>
                                @endif

                            </div>
                        @else
                            There seems to be an error.
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
