<?php 

 ?>
<div class="row">
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Personeller</span>
        <span class="info-box-number">
          10
          <small>%</small>
        </span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Aldığım Projeler</span>
        <span class="info-box-number">41,410</span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->

  <!-- fix for small devices only -->
  <div class="clearfix hidden-md-up"></div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Verdiğim Projeler</span>
        <span class="info-box-number">760</span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Firmalar</span>
        <span class="info-box-number">2,000</span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->


<div class="row">
  <!-- TABLE: LATEST ORDERS -->
  <div class="col-md-4">

    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Create Event</h3>
      </div>
      <div class="card-body">
        <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
          <ul class="fc-color-picker" id="color-chooser">
            <li><a class="text-primary" href="#"><i class="fas fa-square"></i></a></li>
            <li><a class="text-warning" href="#"><i class="fas fa-square"></i></a></li>
            <li><a class="text-success" href="#"><i class="fas fa-square"></i></a></li>
            <li><a class="text-danger" href="#"><i class="fas fa-square"></i></a></li>
            <li><a class="text-muted" href="#"><i class="fas fa-square"></i></a></li>
          </ul>
        </div>
        <!-- /btn-group -->
        <div class="input-group">
          <input id="new-event" type="text" class="form-control" placeholder="Event Title">

          <div class="input-group-append">
            <button id="add-new-event" type="button" class="btn btn-primary">Add</button>
          </div>
        </div>
      </div>

      <div class="card-body">
        <!-- the events -->
        <div id="external-events">
          <div class="external-event bg-success">Lunch</div>
          <div class="external-event bg-warning">Go home</div>
          <div class="external-event bg-info">Do homework</div>
          <div class="external-event bg-primary">Work on UI design</div>
          <div class="external-event bg-danger">Sleep tight</div>
          <div class="checkbox">
            <label for="drop-remove">
              <input type="checkbox" id="drop-remove">
              remove after drop
            </label>
          </div>
        </div>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->

    <!-- /.col -->

  </div>
  <div class="col-md-4">
    <!-- DONUT CHART -->
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">Kasa Hareketleri</h3>

        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-card-widget="remove">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
      </div>
      <!-- /.card-body -->
    </div>
  </div>
  <!-- /.card -->
</div>

<script src="plugins/chart.js/Chart.min.js"></script>
<script>
  //-------------
  //- DONUT CHART -
  //-------------
  // Get context with jQuery - using jQuery's .get() method.
  var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
  var donutData = {
    labels: [
      'Chrome',
      'IE',
      'FireFox',
      'Safari',
      'Opera',
      'Navigator',
    ],
    datasets: [
      {
        data: [700, 500, 400, 600, 300, 100],
        backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
      }
    ]
  }
  var donutOptions = {
    maintainAspectRatio: false,
    responsive: true,
  }
  //Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  new Chart(donutChartCanvas, {
    type: 'doughnut',
    data: donutData,
    options: donutOptions
  })



  /* ADDING EVENTS */
  var currColor = '#3c8dbc' //Red by default
  // Color chooser button
  $('#color-chooser > li > a').click(function (e) {
    e.preventDefault()
    // Save color
    currColor = $(this).css('color')
    // Add color effect to button
    $('#add-new-event').css({
      'background-color': currColor,
      'border-color': currColor
    })
  })
  $('#add-new-event').click(function (e) {
    e.preventDefault()
    // Get value and make sure it is not null
    var val = $('#new-event').val()
    if (val.length == 0) {
      return
    }

    // Create events
    var event = $('<div />')
    event.css({
      'background-color': currColor,
      'border-color': currColor,
      'color': '#fff'
    }).addClass('external-event')
    event.text(val)
    $('#external-events').prepend(event)

    // Add draggable funtionality
    ini_events(event)

    // Remove event from text input
    $('#new-event').val('')
  })
</script>