@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Admin Book Budget Year List</div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>
                              Select Year for Book Budget
                            </h4>
                            <ul>
                                @for ($year = 2024; $year >= 2023; $year--)
                                    <li>
                                        <a href="{{ route('admin-book-budget-department-wise', ['iitm_dept_code' => 'ALL', 'year_from_until' => $year . '-' . ($year + 1)]) }}">
                                            {{ $year }}-{{ $year + 1 }}
                                        </a>
                                    </li>
                                @endfor
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
