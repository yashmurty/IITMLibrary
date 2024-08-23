@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Book Budget Department List
                </div>

                <div class="panel-body">
                    <table class="table">
                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="alert alert-error">
                            {{ session('error') }}
                        </div>
                        @endif

                        <caption>Showing Book Budget for the Department: <strong>{{ $iitm_dept_code }}</strong></caption>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Department</th>
                                <th>Year</th>
                                <th>Budget Allocated</th>
                                <th>Budget Spent</th>
                                <th>Budget On Order</th>
                                <th>Budget Available</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!($lac_users_departments_with_budget == null))
                            @foreach ($lac_users_departments_with_budget as $key => $department_budget)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>{{ $department_budget->iitm_dept_code }}</td>
                                <td>
                                    <span class="label label-default">{{ $department_budget->year_from_until }}</span>
                                </td>
                                <td>{{ $department_budget->budget_allocated }}</td>
                                <td>{{ $department_budget->budget_spent }}</td>
                                <td>{{ $department_budget->budget_on_order }}</td>
                                <td>{{ $department_budget->budget_available }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="9">No Book Budget found for: <strong>Department: {{ $iitm_dept_code }}</strong></td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection