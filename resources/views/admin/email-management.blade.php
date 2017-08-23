@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Admin Email Management</div>

                <div class="panel-body">
                  <p>
                    Enter <strong>To</strong> email address below to check email delivery.
                  </p>
                    <form class="form-horizontal" role="form" method="POST" action="{{ URL::route('admin-email-test-post') }}">
                    {!! csrf_field() !!}
                       <div class="col-md-12">
                            <!-- START - ISBN -->
                            <div class="form-group col-md-5 col-sm-12">
                                <label for="inputISBN">Enter email address </label>
                                <input type="text" class="form-control" id="inputEmail" name="inputEmail" placeholder="Enter email address" required>
                            </div>

                        </div>
                        <div class="col-md-12">
                            <!-- START - Submit -->
                            <div class="form-group col-md-5 col-sm-12">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">
                                    <i class="fa fa-btn fa-book"></i>| Test Email
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
