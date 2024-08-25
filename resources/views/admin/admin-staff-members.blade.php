@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Admin Staff Members</div>

                <div class="panel-body">

                    <table class="table">
                        <caption>Admin Staff Members</caption>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Employee ID</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!($admin_users == null))
                            @foreach ($admin_users as $key => $admin_user)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td><img src="https://photos.iitm.ac.in/byid.php?id={{ $admin_user->iitm_id }}" style="height:50px;"></td>
                                <td>{{ $admin_user->name }}</td>
                                <td>{{ $admin_user->iitm_id }}</td>
                                <td>{{ $admin_user->email }}</td>
                                <td><span class="label label-default">{{ $admin_user->role }}</span></td>

                                <td><a href="#" class="btn btn-danger" disabled>Edit</a></td>
                            </tr>
                            @endforeach
                            @else
                            No Admin Staff Users found.
                            @endif

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection