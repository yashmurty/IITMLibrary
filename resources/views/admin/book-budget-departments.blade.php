@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="{{ URL::route('admin-book-budget-year-list') }}">
                        <i class="fa fa-btn fa-money"></i>| Admin Book Budget
                    </a> -> Department List
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

                        <caption>Showing Book Budget for the year: <strong>{{ $year_from_until }}</strong></caption>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Department</th>
                                <th>Year</th>
                                <th>Budget Allocated</th>
                                <th>Budget Spent</th>
                                <th>Budget On Order</th>
                                <th>Budget Available</th>
                                <th>View</th>
                                <th>Edit</th>
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
                                <td>₹{{ $department_budget->budget_allocated }}</td>
                                <td>₹{{ $department_budget->budget_spent }}</td>
                                <td>₹{{ $department_budget->budget_on_order }}</td>
                                <td>₹{{ $department_budget->budget_available }}</td>
                                <td><a href="{{ url('/') }}/admin/book-budget-departments/{{ $department_budget->iitm_dept_code }}/ALL" class="btn btn-primary">View Department</a></td>
                                <td><button type="button" data-toggle="modal" data-target="#editModal"
                                        class="btn btn-info btn-s edit-btn"
                                        data-dept="{{ $department_budget->iitm_dept_code }}"
                                        data-year="{{ $department_budget->year_from_until }}"
                                        data-allocated="{{ $department_budget->budget_allocated }}"
                                        data-spent="{{ $department_budget->budget_spent }}"
                                        data-onorder="{{ $department_budget->budget_on_order }}"
                                        data-available="{{ $department_budget->budget_available }}">Edit Budget</button>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="9">No Book Budget found for: <strong>Year: {{ $year_from_until }}</strong> and <strong>Department: {{ $iitm_dept_code }}</strong></td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editModalLabel">Edit Book Budget</h4>
            </div>
            <div class="modal-body">
                <form id="editBudgetForm">
                    <div class="form-group">
                        <label for="editModalDepartment">Department:</label>
                        <input type="text" class="form-control" id="editModalDepartment" readonly>
                    </div>
                    <div class="form-group">
                        <label for="editModalYear">Year:</label>
                        <input type="text" class="form-control" id="editModalYear" readonly>
                    </div>
                    <div class="form-group">
                        <label for="editModalBudgetAllocated">Budget Allocated:</label>
                        <input type="text" class="form-control budget-input" id="editModalBudgetAllocated">
                    </div>
                    <div class="form-group">
                        <label for="editModalBudgetSpent">Budget Spent:</label>
                        <input type="text" class="form-control budget-input" id="editModalBudgetSpent">
                    </div>
                    <div class="form-group">
                        <label for="editModalBudgetOnOrder">Budget On Order:</label>
                        <input type="text" class="form-control budget-input" id="editModalBudgetOnOrder">
                    </div>
                    <div class="form-group">
                        <label for="editModalBudgetAvailable">Budget Available:</label>
                        <input type="text" class="form-control" id="editModalBudgetAvailable" readonly>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="editBookBudgetFunction()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<form class="form-horizontal" id="BookBudgetEditForm" role="form" method="POST" action="{{ url('admin/book-budget-departments') }}">
    {!! csrf_field() !!}
    <input type="hidden" name="_method" value="POST">
    <input type="hidden" id="edit-department" name="edit-department" value="">
    <input type="hidden" id="edit-year" name="edit-year" value="">
    <input type="hidden" id="edit-budget-allocated" name="edit-budget-allocated" value="">
    <input type="hidden" id="edit-budget-spent" name="edit-budget-spent" value="">
    <input type="hidden" id="edit-budget-on-order" name="edit-budget-on-order" value="">
    <input type="hidden" id="edit-budget-available" name="edit-budget-available" value="">
</form>
@endsection

@section('jscontent')
<script type="text/javascript">
    $(document).ready(function() {
        $('.edit-btn').on('click', function() {
            var dept = $(this).data('dept');
            var year = $(this).data('year');
            var allocated = String($(this).data('allocated'));
            var spent = String($(this).data('spent'));
            var onorder = String($(this).data('onorder'));
            var available = String($(this).data('available'));

            var parseFormattedNumber = function(value) {
                return parseFloat(value.replace(/,/g, '')) || 0;
            };

            $('#editModalDepartment').val(dept);
            $('#editModalYear').val(year);
            $('#editModalBudgetAllocated').val(parseFormattedNumber(allocated));
            $('#editModalBudgetSpent').val(parseFormattedNumber(spent));
            $('#editModalBudgetOnOrder').val(parseFormattedNumber(onorder));
            $('#editModalBudgetAvailable').val(parseFormattedNumber(available));

            calculateBudgetAvailable();
        });

        $('.budget-input').on('input', function() {
            calculateBudgetAvailable();
        });
    });

    function calculateBudgetAvailable() {
        var parseFormattedNumber = function(value) {
            return parseFloat(value.replace(/,/g, '')) || 0;
        };

        var allocated = parseFormattedNumber($('#editModalBudgetAllocated').val());
        var spent = parseFormattedNumber($('#editModalBudgetSpent').val());
        var onOrder = parseFormattedNumber($('#editModalBudgetOnOrder').val());

        var available = allocated - spent - onOrder;
        $('#editModalBudgetAvailable').val(available.toFixed(2));
    }

    function editBookBudgetFunction() {
        var parseFormattedNumber = function(value) {
            return parseFloat(value.replace(/,/g, '')) || 0;
        };

        document.getElementById("edit-department").value = document.getElementById("editModalDepartment").value;
        document.getElementById("edit-year").value = document.getElementById("editModalYear").value;
        document.getElementById("edit-budget-allocated").value = parseFormattedNumber(document.getElementById("editModalBudgetAllocated").value);
        document.getElementById("edit-budget-spent").value = parseFormattedNumber(document.getElementById("editModalBudgetSpent").value);
        document.getElementById("edit-budget-on-order").value = parseFormattedNumber(document.getElementById("editModalBudgetOnOrder").value);
        document.getElementById("edit-budget-available").value = parseFormattedNumber(document.getElementById("editModalBudgetAvailable").value);
        document.getElementById("BookBudgetEditForm").submit();
    }
</script>
@endsection