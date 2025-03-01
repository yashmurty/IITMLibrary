@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Admin Staff Members</h4>
                    <div class="btn-group pull-right">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addUserModal">
                            <i class="fa fa-plus"></i> Add User
                        </button>
                    </div>
                </div>

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
                                        data-useriitmid="{{ $admin_user->iitm_id }}"
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

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addUserModalLabel">Add New Admin User</h4>
            </div>
            <form id="addUserForm" method="POST" action="{{ url('admin/staffmembers/add') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label for="newUserIITMId">IITM ID: <span class="text-danger">*</span></label>
                        <input type="text" name="iitm_id" id="newUserIITMId" class="form-control" required>
                        <small class="help-block">Enter a valid IITM ID from the users table.</small>
                    </div>
                    <div class="form-group">
                        <label for="newUserRole">Role:</label>
                        <select name="role" id="newUserRole" class="form-control">
                            @foreach(config('roles') as $role_key => $role_value)
                            <option value="{{ $role_value }}" {{ $role_key == 'staff_approver' ? 'selected' : '' }}>{{ $role_key }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
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
                        <label for="userIITMId">IITM ID: <span class="text-danger">*</span></label>
                        <input type="text" name="iitm_id" id="editUserIITMId" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="userName">User Name:</label>
                        <p id="editUserName" class="form-control-static"></p>
                    </div>
                    <div class="form-group">
                        <label for="userEmail">Email:</label>
                        <p id="editUserEmail" class="form-control-static"></p>
                    </div>
                    <div class="form-group">
                        <label for="userRole">Role:</label>
                        <select name="role" id="editUserRole" class="form-control">
                            @foreach(config('roles') as $role_key => $role_value)
                            <option value="{{ $role_value }}">{{ $role_key }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Admin Status:</label>
                        <div>
                            <a href="#" id="deleteUserLink" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal">
                                <i class="fa fa-trash"></i> Delete Admin User
                            </a>
                            <p class="help-block small">This will remove the user from admin staff members, but won't delete the main user account.</p>
                        </div>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove this user from admin staff members?</p>
                <p class="text-danger"><strong>This action cannot be undone.</strong></p>
            </div>
            <div class="modal-footer">
                <form id="deleteUserForm" method="POST" action="">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('jscontent')
<script type="text/javascript">
    // Initialize the edit modal with user data
    $('#editRoleModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var userId = button.data('userid');
        var userIITMId = button.data('useriitmid');
        var userName = button.data('username');
        var userEmail = button.data('useremail');
        var userRole = button.data('userrole');

        var modal = $(this);
        var form = modal.find('form');
        form.attr('action', '{{ url("admin/staffmembers") }}/' + userId + '/edit-role');
        modal.find('#editUserIITMId').val(userIITMId);
        modal.find('#editUserName').text(userName || 'Not available');
        modal.find('#editUserEmail').text(userEmail || 'Not available');
        modal.find('#editUserRole').val(userRole);

        // Set the correct delete URL
        $('#deleteUserLink').data('userid', userId);
    });

    // When delete confirmation modal opens, set the correct form action
    $('#confirmDeleteModal').on('show.bs.modal', function(event) {
        var link = $(event.relatedTarget);
        var userId = link.data('userid');
        var deleteForm = $('#deleteUserForm');
        deleteForm.attr('action', '{{ url("admin/staffmembers") }}/' + userId + '/delete');
    });
</script>
@endsection