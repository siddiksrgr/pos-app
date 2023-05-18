@extends('layouts.app')

@section('header', 'Dashboard')

@section('content')
<div class="row">
  <div class="col-md-6">
    <div class="row">
      <div class="col-md-12">
        <div class="card bg-gradient-success">
          <div class="card-header">
            <h3 class="card-title">Sales Today</h3>
          </div>
          <div class="card-body">
            <h4>{{ $salesToday->count() }} Sales</h4>
          </div>
          <div class="card-footer">
            <h4>Rp. {{ convert_rupiah($salesToday->sum('total')) }}</h4>
          </div>
        </div>
        <div class="card bg-gradient-primary">
          <div class="card-header">
            <h3 class="card-title">Profit Today</h3>
          </div>
          <div class="card-body">
            <h4>Rp. {{ convert_rupiah($salesToday->sum('profit')) }}</h4>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- DONUT CHART -->
  <div class="col-md-6">
    <div class="card card-danger">
      <div class="card-header">
        <h3 class="card-title">Best Selling Product</h3>
      </div>
      <div class="card-body">
        <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
      </div>
    </div>
  </div>
  <!-- BAR CHART -->
  <div class="col-lg-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">This Month Sales And Profit Chart</h3>
      </div>
      <div class="card-body">
        <div class="chart">
          <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<!-- ChartJS -->
<script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
<script type="text/javascript">

  var data_bar = '{!! json_encode($data_bar) !!}';
  var laberBarY = '{!! json_encode($laberBarY) !!}';
  var data_donut = '{!! json_encode($data_donut) !!}';
  var label_donut = '{!! json_encode($label_donut) !!}';

  $(function () {
    //-------------
    //- BAR CHART -
    //-------------
    var areaChartData = {
      labels  : JSON.parse(laberBarY),
      datasets: JSON.parse(data_bar)
    }

    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = $.extend(true, {}, areaChartData)

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false,
      scales: {
            yAxes: [{
                ticks: {
                  stepSize: 50000
                }
            }]
          }
    }

    new Chart(barChartCanvas, {
      type: 'bar',
      data: barChartData,
      options: barChartOptions
    })

    //-------------
    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
    var donutData        = {
      labels: JSON.parse(label_donut),
      datasets: [
        {
          data: JSON.parse(data_donut),
          backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
        }
      ]
    }
    var donutOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    new Chart(donutChartCanvas, {
      type: 'doughnut',
      data: donutData,
      options: donutOptions
    })

  })
</script>
@endsection
