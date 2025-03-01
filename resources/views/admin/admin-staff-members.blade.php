@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Admin Staff Members</div>

                <div class="panel-body">

                    <table class="table">
                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                        @endif
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

                                <td>
                                    <button type="button" class="btn btn-primary"
                                        data-toggle="modal"
                                        data-target="#editRoleModal"
                                        data-userid="{{ $admin_user->id }}"
                                        data-username="{{ $admin_user->name }}"
                                        data-useremail="{{ $admin_user->email }}"
                                        data-userrole="{{ $admin_user->role }}">
                                        Edit User
                                    </button>
                                </td>
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

<!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1" role="dialog" aria-labelledby="editRoleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editRoleModalLabel">Edit User Information</h4>
            </div>
            <form id="editRoleForm" method="POST" action="" id="edit-user-form">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label for="userName">User Name:</label>
                        <input type="text" name="name" id="editUserName" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="userEmail">Email:</label>
                        <input type="email" name="email" id="editUserEmail" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="userRole">Role:</label>
                        <select name="role" id="editUserRole" class="form-control">
                            @foreach(config('roles') as $role_key => $role_value)
                            <option value="{{ $role_value }}">{{ $role_key }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('jscontent')
<script type="text/javascript">
    // Initialize the modal with user data
    $('#editRoleModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var userId = button.data('userid');
        var userName = button.data('username');
        var userEmail = button.data('useremail');
        var userRole = button.data('userrole');

        var modal = $(this);
        var form = modal.find('form');
        form.attr('action', '{{ url("admin/staffmembers") }}/' + userId + '/edit-role');
        modal.find('#editUserName').val(userName);
        modal.find('#editUserEmail').val(userEmail);
        modal.find('#editUserRole').val(userRole);
    });
</script>
@endsection