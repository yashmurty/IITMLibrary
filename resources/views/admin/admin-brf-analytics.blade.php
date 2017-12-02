@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Admin BRF Analytics</div>

                <div class="panel-body">
                    <div class ="row">
                        <div class="col-md-12">
                            Showing <strong>Complete</strong> data.
                            <!-- Single button -->
                            <div class="btn-group">
                              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                View Yearwise <span class="caret"></span>
                              </button>
                              <ul class="dropdown-menu">
                                <li><a href="{{ url('/admin/brf-analytics/2018-2019') }}">2018-2019</a></li>
                                <li><a href="{{ url('/admin/brf-analytics/2017-2018') }}">2017-2018</a></li>
                                <li><a href="{{ url('/admin/brf-analytics/2016-2017') }}">2016-2017</a></li>
                              </ul>
                            </div>

                            <hr>
                            <p style="padding:10px;" class="bg-primary">Total BRF Requests made till date : <strong> {{ $brf_all_count }} </strong></p>

                            <p style="padding:10px;" class="bg-success">Approved Requests downloaded for Processing : <strong> {{ $brf_approved_downloaded_count }} </strong></p>
                            <p style="padding:10px;" class="bg-warning">Pending BRF Requests Approved by LAC Members : <strong> {{ $brf_pending_lac_approved_count }} </strong></p>
                            <p style="padding:10px;" class="bg-warning">Pending BRF Requests Approved by Librarian : <strong> {{ $brf_pending_librarian_approved_count }} </strong></p>

                            <p style="padding:10px;" class="bg-danger">Requests Denied by Librarian : <strong> {{ $brf_pending_librarian_denied_count }} </strong></p>
                            <p style="padding:10px;" class="bg-danger">Requests Denied by LAC Members : <strong> {{ $brf_pending_lac_denied_count }} </strong></p>

                            <p style="padding:10px;" class="bg-info">Pending Requests that are new for LAC Approval : <strong> {{ $brf_new_pending_lac_count }} </strong></p>
                        </div>
                        <div class="col-md-12">
                            <!--Div that will hold the pie chart-->
                            <div id="chart_div"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('jscontent')
<!--Load the AJAX API-->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

  // Load the Visualization API and the corechart package.
  google.charts.load('current', {'packages':['corechart']});

  // Set a callback to run when the Google Visualization API is loaded.
  google.charts.setOnLoadCallback(drawChart);

  // Callback that creates and populates a data table,
  // instantiates the pie chart, passes in the data and
  // draws it.
  function drawChart() {

    // Create the data table.
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Category');
    data.addColumn('number', 'Requests');
    data.addRows([
        ['Approved Requests downloaded for Processing', {{ $brf_approved_downloaded_count }}],
        ['Pending BRF Requests Approved by LAC Members', {{ $brf_pending_lac_approved_count }}],
        ['Pending BRF Requests Approved by Librarian', {{ $brf_pending_librarian_approved_count }}],
        ['Requests Denied by Librarian', {{ $brf_pending_librarian_denied_count }}],
        ['Requests Denied by LAC Members', {{ $brf_pending_lac_denied_count }}],
        ['Pending Requests that are new for LAC Approval', {{ $brf_new_pending_lac_count }}]
    ]);

    // Set chart options
    var options = {'title':'BRF Analytics',
                   'colors': ['#3c763d', '#8a6d3b', '#8a6d3b', '#a94442', '#a94442', '#31708f'],
                   'height':300};

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    chart.draw(data, options);
  }
</script>
@endsection
