<p style="padding:10px;" class="bg-primary">BRF Requests for Approval :
    @if(($user_brfs == null))
    0
    @else
    <strong> {{ count($user_brfs) }} </strong>
    @endif
</p>

<table class="table">
    <caption>
        Status of requests under you submitted via Book Requisition Form.
        Your Role: <strong> {{ $auth_usertype }} </strong>
    </caption>
    <thead>
        <tr>
            <th>#</th>
            <th>Faculty</th>
            <th>Department</th>
            <th>Doctype</th>
            <th>Author</th>
            <th>Title</th>
            <th>LAC Status</th>
            <th>Librarian Status</th>
            <th>Remarks</th>
            <th>Edit</th>
        </tr>
    </thead>
    <tbody>
        @if(!($user_brfs == null))
        @foreach ($user_brfs as $key => $user_brf)
        @if( $user_brf->download_status == null)
        <tr>
            @elseif( $user_brf->download_status == "downloaded" )
        <tr class="success">
            @endif
            <th scope="row">
                <!-- {{ $key + 1 }}  -->
                #{{ $user_brf->id }} </br>
                <span class="label label-default">{{ \Carbon\Carbon::parse($user_brf->created_at)->format('Y-m-d') }}</span>
            </th>
            <td>{{ $user_brf->faculty }}</td>
            <td>{{ $user_brf->iitm_dept_code }}</td>
            <td>{{ $user_brf->doctype }}</td>
            <td>{{ $user_brf->author }}</td>
            <td>{{ $user_brf->title }}</td>

            <!-- LAC Status -->
            <td>
                @if( $user_brf->lac_status == null)
                <i class="fa fa-clock-o"></i>
                @elseif( $user_brf->lac_status == "approved" )
                <i class="fa fa-check-circle" style="color:green"></i>
                @else
                <i class="fa fa-times-circle" style="color:red"></i>
                @endif

                @if($user_brf->lac_status_date)
                <span class="label label-default">{{ \Carbon\Carbon::parse($user_brf->lac_status_date)->format('Y-m-d') }}</span>
                @endif
            </td>

            <!-- Librarian Status -->
            <td>
                @if( $user_brf->librarian_status == null)
                <i class="fa fa-clock-o"></i>
                @elseif( $user_brf->librarian_status == "approved" )
                <i class="fa fa-check-circle" style="color:green;"></i>
                @else
                <i class="fa fa-times-circle" style="color:red"></i>
                @endif

                @if($user_brf->librarian_status_date)
                <span class="label label-default">{{ \Carbon\Carbon::parse($user_brf->librarian_status_date)->format('Y-m-d') }}</span>
                @endif
            </td>

            <td>{{ $user_brf->remarks }}</td>
            <td><a href="{{ url('staff-approver/requeststatus/brf/') }}/{{ $user_brf->id }}" class="btn btn-primary">View</a></td>
        </tr>
        @endforeach
        @else
        You don't have any approval requests yet.
        @endif

    </tbody>
</table>