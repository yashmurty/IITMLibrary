@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Admin Book Budget Department List</div>

                <div class="panel-body">

                    <table class="table">
                        <caption>Showing Book Budget for the year: <strong>{{ $year_from_until }}</strong></caption>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Department Code</th>
                                <th>Budget Allocated</th>
                                <th>Budget Spent</th>
                                <th>Budget On Order</th>
                                <th>Budget Available</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!($lac_users_departments == null))
                            @foreach ($lac_users_departments as $key => $lac_users_department)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>{{ $lac_users_department->iitm_dept_code }}</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>

                                <td><a href="{{ url('/') }}/admin/book-budget-departments/{{ $lac_users_department->iitm_dept_code }}" class="btn btn-primary">View</a></td>
                            </tr>
                            @endforeach
                            @else
                            No Book Users found.
                            @endif

                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection