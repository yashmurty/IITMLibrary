@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">LAC - View BRF Request</div>
                <div class="panel-body">
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
                                <form class="form-horizontal" id="BRFapprovalForm" role="form" method="POST" action="{{ url('lac/requeststatus/brf') }}">
                                    {!! csrf_field() !!}
                                    <input type="hidden" id="brf_id" name="brf_id" value="{{ $lac_user_brf->id }}">
                                    <input type="hidden" id="lac_status" name="lac_status" value="">
                                    <input type="hidden" id="remarks" name="remarks" value="">
                                </form>
                                @if( $lac_user_brf->librarian_status == "approved" )

                                <div class="row">
                                    <div class="col-md-6">
                                        <button class="btn btn-danger btn-lg btn-block disabled" disabled="">Deny</button>
                        
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-success btn-lg btn-block disabled" disabled="">Approve</button>
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="submit" data-toggle="modal" data-target="#denyModal" class="btn btn-danger btn-lg btn-block">Deny</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" onclick="approveFunction()" class="btn btn-success btn-lg btn-block">Approve</button>
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


<!-- Modal -->
<div class="modal fade" id="denyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <input type="text" name="modalremarks" class="col-md-12 form-control" id="modalremarks" placeholder="Reason for denying the request.">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" onclick="denyFunction()">Deny Book Request</button>
      </div>
    </div>
  </div>
</div>
@endsection


@section('jscontent')
<script type="text/javascript">

    function approveFunction () {
        document.getElementById("lac_status").value = "approved";
        document.getElementById("remarks").value = "Approved by LAC";
        document.getElementById("BRFapprovalForm").submit();
    }

    function denyFunction () {
        document.getElementById("lac_status").value = "denied";
        document.getElementById("remarks").value = document.getElementById("modalremarks").value;
        document.getElementById("BRFapprovalForm").submit();
    }
    
</script>
@endsection