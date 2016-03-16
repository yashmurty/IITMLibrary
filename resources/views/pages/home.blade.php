@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <p>
                    You are logged in! Visit the <a href="{{ URL::route('bookrequisitionform') }}">Book Requisition Form</a>
                    </p>
                    <p>
                        View <a href="{{ URL::route('requeststatus') }}">Request Status</a> of previous requests.
                    </p>
                    <hr>
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/bookrequisitionformisbn') }}">
                    {!! csrf_field() !!}
                       <div class="col-md-12">
                            <h4>Autofill Book Requisition Form data via ISBN</h4>
                            <!-- START - ISBN -->
                            <div class="form-group col-md-5 col-sm-12">
                                <label for="inputISBN">Enter ISBN </label>
                                <input type="text" class="form-control" id="inputISBN" name="inputISBN" placeholder="Enter 10 or 13 Digit ISBN" required>
                            </div>
                            
                        </div>   
                        <div class="col-md-12">
                            <!-- START - Submit -->
                            <div class="form-group col-md-5 col-sm-12">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">
                                    <i class="fa fa-btn fa-book"></i>| Fetch Book Details
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
