<div id="admin-requeststatus-excel-export">
    <p>
        Export the requests that have been aprroved by the Librarian
        <a href="{{ URL::route('adminrequeststatus-export-excel') }}" class="btn btn-primary">Export to Excel</a>
    </p>
    <p>
        Export the requests that are pending for approval by Librarian
        <a href="{{ URL::route('adminrequeststatus-pending-export-excel') }}" class="btn btn-default">Export to Excel</a>
    </p>
</div>