@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="panel panel-default">
        <div class="panel-heading">Staff Approver Admin - View BRF Request</div>
        <div class="panel-body">
          @if(!($admin_user_brf == null))
          <h4 class="text-center">{{ $admin_user_brf->title }}</h4>
          <hr>
          <div class="col-md-8 col-md-offset-2">
            <p>
              <strong>BRF ID :</strong> {{ $admin_user_brf->id }}
              <span class="label label-default">{{ \Carbon\Carbon::parse($admin_user_brf->created_at)->format('Y-m-d') }}</span>
            </p>
            <strong>Document Type :</strong> {{ $admin_user_brf->doctype }}</br>
            <strong>Author :</strong> {{ $admin_user_brf->author }}</br>
            <strong>Title :</strong> {{ $admin_user_brf->title }}</br>
            <strong>Publisher :</strong> {{ $admin_user_brf->publisher }}</br>
            <strong>Vendor Name (Agency) :</strong> {{ $admin_user_brf->agency }}</br>
            <strong>ISBN :</strong> {{ $admin_user_brf->isbn  }}</br>
            <strong>Volume :</strong> {{ $admin_user_brf->volumne }}</br>
            <strong>Price :</strong> {{ $admin_user_brf->price }}</br>
            <strong>Section Catalogue :</strong> {{ $admin_user_brf->sectioncatalogue }}</br>
            <strong>Number of Copies :</strong> {{ $admin_user_brf->numberofcopies }}</br>
            <hr>

            <!-- Replace the old status display with this workflow table -->
            <table class="table">
              <caption>BRF Approval Workflow Status</caption>
              <thead>
                <tr>
                  <th>#</th>
                  <th>Step Name</th>
                  <th>Date </th>
                  <th>Person</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>Faculty Submits BRF</td>
                  <td>
                    <span class="label label-default">{{ \Carbon\Carbon::parse($admin_user_brf->created_at)->format('Y-m-d') }}</span>
                  </td>
                  <td>{{ $admin_user_brf->faculty }}</td>
                  <td>
                    <i class="fa fa-check-circle" style="color:green"></i>
                  </td>

                </tr>
                <tr>
                  <td>2</td>
                  <td>LAC Approves BRF</td>
                  <td>
                    @if($admin_user_brf->lac_status_date)
                    <span class="label label-default">{{ \Carbon\Carbon::parse($admin_user_brf->lac_status_date)->format('Y-m-d') }}</span>
                    @else
                    -
                    @endif
                  </td>
                  <td><strong>{{ $admin_user_brf->iitm_dept_code }}</strong> Deptartment LAC</td>
                  <td>
                    @if($admin_user_brf->lac_status == null)
                    <i class="fa fa-clock-o"></i>
                    @elseif($admin_user_brf->lac_status == "approved")
                    <i class="fa fa-check-circle" style="color:green"></i>
                    @else
                    <i class="fa fa-times-circle" style="color:red"></i>
                    @endif
                  </td>
                </tr>
                <tr>
                  <td>3</td>
                  <td>Librarian Approve/Deny</td>
                  <td>
                    @if($admin_user_brf->librarian_status_date)
                    <span class="label label-default">{{ \Carbon\Carbon::parse($admin_user_brf->librarian_status_date)->format('Y-m-d') }}</span>
                    @else
                    -
                    @endif
                  </td>
                  <td>{{ $librarian_approver_name ?: '-' }}</td>
                  <td>
                    @if($admin_user_brf->librarian_status == null)
                    <i class="fa fa-clock-o"></i>
                    @elseif($admin_user_brf->librarian_status == "approved")
                    <i class="fa fa-check-circle" style="color:green"></i>
                    @else
                    <i class="fa fa-times-circle" style="color:red"></i>
                    @endif
                  </td>
                </tr>
                <tr>
                  <td>4</td>
                  <td>Librarian Excel Download</td>
                  <td>
                    @if($admin_user_brf->download_status_date)
                    <span class="label label-default">{{ \Carbon\Carbon::parse($admin_user_brf->download_status_date)->format('Y-m-d') }}</span>
                    @else
                    -
                    @endif
                  </td>
                  <td>{{ $downloader_approver_name ?: '-' }}</td>
                  <td>
                    @if($admin_user_brf->download_status == null)
                    <i class="fa fa-clock-o"></i>
                    @elseif($admin_user_brf->download_status == "downloaded")
                    <i class="fa fa-check-circle" style="color:green"></i>
                    @else
                    <i class="fa fa-times-circle" style="color:red"></i>
                    @endif
                  </td>
                </tr>
                <tr>
                  <td>5</td>
                  <td>Librarian Procurment Finished</td>
                  <td>-</td>
                  <td>-</td>
                  <td>
                    <i class="fa fa-clock-o"></i>
                  </td>
                </tr>
              </tbody>
            </table>
            <hr>


            <div class="row">
              <div class="col-md-12">
                <p>
                  <strong>Remarks</strong>
                  <button type="submit" data-toggle="modal" data-target="#editModal" class="btn btn-info btn-s">Edit</button>
                </p>
                <div class="well" style="margin-top: 10px;">
                  {!! nl2br(e($admin_user_brf->remarks)) !!}
                </div>
                <button type="button" data-toggle="modal" data-target="#emailModal" class="btn btn-primary btn-s">
                  <i class="fa fa-btn fa-envelope"></i>| Manually send updated email
                </button>

              </div>
            </div>

            <hr>
            <form class="form-horizontal" id="BRFapprovalForm" role="form" method="POST" action="{{ url('staff-approver/requeststatus/brf') }}">
              {!! csrf_field() !!}
              <input type="hidden" id="brf_id" name="brf_id" value="{{ $admin_user_brf->id }}">
              <input type="hidden" id="librarian_status" name="librarian_status" value="">
              <input type="hidden" id="remarks" name="remarks" value="">
            </form>

            <form class="form-horizontal" id="BRFeditForm" role="form" method="POST" action="{{ url('staff-approver/requeststatus/brf') }}/{{ $admin_user_brf->id }}">
              {!! csrf_field() !!}
              <input type="hidden" name="_method" value="PUT">
              <input type="hidden" id="edit-remarks" name="edit-remarks" value="">
            </form>

            <form class="form-horizontal" id="sendEmailForm" role="form" method="POST" action="{{ url('staff-approver/requeststatus/brf/send-email') }}/{{ $admin_user_brf->id }}">
              {!! csrf_field() !!}
            </form>

            @if( $admin_user_brf->librarian_status == "approved" )

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
                <button type="submit" data-toggle="modal" data-target="#approveModal" class="btn btn-success btn-lg btn-block">Approve</button>
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
<div class="modal fade" id="denyModal" tabindex="-1" role="dialog" aria-labelledby="denyModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="denyModalLabel">Deny Request</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <input type="text" name="modalremarks" class="col-md-12 form-control" id="denyModalRemarks" placeholder="Reason for denying the request.">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" onclick="denyFunction()">Deny Book Request</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="approveModalLabel">Approve Request</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <input type="text" name="modalremarks" class="col-md-12 form-control" id="approveModalRemarks" value="Approved by Librarian" placeholder="Reason for Approving the request.">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" onclick="approveFunction()">Approve Book Request</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="editModalLabel">Edit Request</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <textarea name="modalremarks" class="col-md-12 form-control" id="editModalRemarks" rows="4">{{ $admin_user_brf->remarks }}</textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" onclick="editRemarkFunction()">Edit Book Request</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="emailModalLabel">Send Update Email</h4>
      </div>
      <div class="modal-body">
        <p>The following email will be sent to the user:</p>
        <div class="well" style="margin-top: 10px;">
          {!! $email_preview !!}
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="sendEmailUpdate()"><i class="fa fa-btn fa-envelope"></i>| Send Email</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('jscontent')
<script type="text/javascript">
  function approveFunction() {
    document.getElementById("librarian_status").value = "approved";
    document.getElementById("remarks").value = document.getElementById("approveModalRemarks").value;
    document.getElementById("BRFapprovalForm").submit();
  }

  function denyFunction() {
    document.getElementById("librarian_status").value = "denied";
    document.getElementById("remarks").value = document.getElementById("denyModalRemarks").value;
    document.getElementById("BRFapprovalForm").submit();
  }

  function editRemarkFunction() {
    document.getElementById("edit-remarks").value = document.getElementById("editModalRemarks").value;
    document.getElementById("BRFeditForm").submit();
  }

  function sendEmailUpdate() {
    document.getElementById("sendEmailForm").submit();
  }
</script>
@endsection