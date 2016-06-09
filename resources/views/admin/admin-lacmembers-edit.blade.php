@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit LAC Member</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/lacmembers/') }}/{{ $lac_user->iitm_dept_code }}/edit">
                        {!! csrf_field() !!}

                        <!-- <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="col-md-4">Name</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> -->

                        <!-- START - Department Code -->
                        <div class="form-group col-md-12">
                            <label for="input_iitm_dept_code">Department Code </label>
                            <input type="text" class="form-control" id="input_iitm_dept_code" name="input_iitm_dept_code" value="{{ $lac_user->iitm_dept_code }}" readonly="">
                        </div>
                        <!-- END - Department Code -->

                        <!-- START - IITM ID -->
                        <div class="form-group col-md-12">
                            <label for="input_iitm_id">IITM ID </label>
                            <input type="text" class="form-control" id="input_iitm_id" name="input_iitm_id" value="{{ $lac_user->iitm_id }}" required="">
                        </div>
                        <!-- END - IITM ID -->

                        <!-- START - Name -->
                        <div class="form-group col-md-12">
                            <label for="input_name">Name </label>
                            <input type="text" class="form-control" id="input_name" name="input_name" value="{{ $lac_user->name }}" required="">
                        </div>
                        <!-- END - Name -->

                        <!-- START - Email ID -->
                        <div class="form-group col-md-12">
                            <label for="input_lac_email_id">Email ID </label>
                            <input type="text" class="form-control" id="input_lac_email_id" name="input_lac_email_id" value="{{ $lac_user->lac_email_id }}" required="">
                        </div>
                        <!-- END - Email ID -->

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3 ">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">
                                    <i class="fa fa-btn fa-check"></i>| Update LAC Member
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
