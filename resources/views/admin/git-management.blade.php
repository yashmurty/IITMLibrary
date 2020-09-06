@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Admin Git Management</div>

                <div class="panel-body">
                    Refresh Code. <br>

                    <a style="margin-top:20px;" class="btn btn-primary col-sm-12 col-md-4" href="{{ URL::route('admin-git-management-git-pull') }}">
                            Click here for git pull
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
