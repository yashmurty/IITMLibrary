@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">LAC Members</div>

                <div class="panel-body">

                    <table class="table">
                        <caption>LAC Members</caption>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Faculty</th>
                                <th>Employee ID</th>
                                <th>Email</th>
                                <th>Department Code</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!($lac_users == null))
                                @foreach ($lac_users as $key => $lac_user)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td><img src="https://photos.iitm.ac.in/byid.php?id={{ $lac_user->iitm_id }}" style="height:50px;"></td>
                                    <td>{{ $lac_user->name }}</td>
                                    <td>{{ $lac_user->iitm_id }}</td>
                                    <td>{{ $lac_user->lac_email_id }}</td>
                                    <td>{{ $lac_user->iitm_dept_code }}</td>


                                    <td><a href="#" class="btn btn-danger">Edit</a></td> 
                                </tr>
                                @endforeach
                            @else
                                No LAC Users found.
                            @endif

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
