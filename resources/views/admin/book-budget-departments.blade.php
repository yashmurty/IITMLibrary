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
                                <th>Year</th>
                                <th>Budget Allocated</th>
                                <th>Budget Spent</th>
                                <th>Budget On Order</th>
                                <th>Budget Available</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!($lac_users_departments_with_budget == null))
                            @foreach ($lac_users_departments_with_budget as $key => $department_budget)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>{{ $department_budget->iitm_dept_code }}</td>
                                <td>{{ $department_budget->year_from_until }}</td>
                                <td>{{ $department_budget->budget_allocated }}</td>
                                <td>{{ $department_budget->budget_spent }}</td>
                                <td>{{ $department_budget->budget_on_order }}</td>
                                <td>{{ $department_budget->budget_available }}</td>

                                <td><a href="{{ url('/') }}/admin/book-budget-departments/{{ $department_budget->iitm_dept_code }}/ALL" class="btn btn-primary">View</a></td>
                            </tr>
                            @endforeach
                            @else
                            No Book Budget found for: <strong>Year: {{ $year_from_until }}</strong> and <strong>Department: {{ $iitm_dept_code }}</strong>
                            @endif

                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection