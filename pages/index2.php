<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/include/requires.php"; ?>

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

<style>

i {
    font-style: italic;
}

.container{
    margin-top:100px;
}

.card {
    box-shadow: 0 0.46875rem 2.1875rem rgba(4, 9, 20, 0.03), 0 0.9375rem 1.40625rem rgba(4, 9, 20, 0.03), 0 0.25rem 0.53125rem rgba(4, 9, 20, 0.05), 0 0.125rem 0.1875rem rgba(4, 9, 20, 0.03);
    border-width: 0;
    transition: all .2s;
}

.card-header:first-child {
    border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0;
}

.card-header {
    display: flex;
    align-items: center;
    border-bottom-width: 1px;
    padding-top: 0;
    padding-bottom: 0;
    padding-right: 0.625rem;
    height: 3.5rem;
    background-color: #fff;
}
.widget-subheading{
    color: #858a8e;
    font-size: 10px;
}
.card-header.card-header-tab .card-header-title {
    display: flex;
    align-items: center;
    white-space: nowrap;
}

.card-header .header-icon {
    font-size: 1.65rem;
    margin-right: 0.625rem;
}

.card-header.card-header-tab .card-header-title {
    display: flex;
    align-items: center;
    white-space: nowrap;
}

.btn-actions-pane-right {
    margin-left: auto;
    white-space: nowrap;
}

.text-capitalize {
    text-transform: capitalize !important;
}

.scroll-area-sm {
    height: 288px;
    overflow-x: hidden;
}

.list-group-item {
    position: relative;
    display: block;
    padding: 0.75rem 1.25rem;
    margin-bottom: -1px;
    background-color: #fff;
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.list-group {
    display: flex;
    flex-direction: column;
    padding-left: 0;
    margin-bottom: 0;
}

.todo-indicator {
    position: absolute;
    width: 4px;
    height: 60%;
    border-radius: 0.3rem;
    left: 0.625rem;
    top: 20%;
    opacity: .6;
    transition: opacity .2s;
}

.bg-warning {
    background-color: #f7b924 !important;
}

.widget-content {
    padding: 1rem;
    flex-direction: row;
    align-items: center;
}

.widget-content .widget-content-wrapper {
    display: flex;
    flex: 1;
    position: relative;
    align-items: center;
}

.widget-content .widget-content-right.widget-content-actions {
    visibility: hidden;
    opacity: 0;
    transition: opacity .2s;
}

.widget-content .widget-content-right {
    margin-left: auto;
}

.btn:not(:disabled):not(.disabled) {
    cursor: pointer;
}

.btn {
    position: relative;
    transition: color 0.15s, background-color 0.15s, border-color 0.15s, box-shadow 0.15s;
}

.btn-outline-success {
    color: #3ac47d;
    border-color: #3ac47d;
}

.btn-outline-success:hover {
    color: #fff;
    background-color: #3ac47d;
    border-color: #3ac47d;
}

.btn-outline-success:hover {
    color: #fff;
    background-color: #3ac47d;
    border-color: #3ac47d;
}
.btn-primary {
    color: #fff;
    background-color: #3f6ad8;
    border-color: #3f6ad8;
}
.btn { 
    position: relative;
    transition: color 0.15s, background-color 0.15s, border-color 0.15s, box-shadow 0.15s;
    outline: none !important;
}

.card-footer{
    background-color: #fff;
}
</style>
<div class="row">
  <!-- TABLE: LATEST ORDERS -->
  <div class="col-md-8">

    <div class="row d-flex justify-content-center">
      <div class="col-md-12">
        <div class="card-hover-shadow-2x mb-3 card">
          <div class="card-header-tab card-header">
            <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="fa fa-tasks"></i>&nbsp;Task Lists</div>

          </div>
          <div class="scroll-area-sm">
            <perfect-scrollbar class="ps-show-limits">
              <div style="position: static;" class="ps ps--active-y">
                <div class="ps-content">
                  <ul class=" list-group list-group-flush">
                    <li class="list-group-item">
                      <div class="todo-indicator bg-warning"></div>
                      <div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                          <div class="widget-content-left mr-2">
                            <div class="custom-checkbox custom-control">
                              <input class="custom-control-input" id="exampleCustomCheckbox12" type="checkbox"><label class="custom-control-label" for="exampleCustomCheckbox12">&nbsp;</label>
                            </div>
                          </div>
                          <div class="widget-content-left">
                            <div class="widget-heading">Call Sam For payments <div class="badge badge-danger ml-2">Rejected</div>
                            </div>
                            <div class="widget-subheading"><i>By Bob</i></div>
                          </div>
                          <div class="widget-content-right">
                            <button class="border-0 btn-transition btn btn-outline-success">
                              <i class="fa fa-check"></i></button>
                            <button class="border-0 btn-transition btn btn-outline-danger">
                              <i class="fa fa-trash"></i>

                            </button>
                          </div>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item">
                      <div class="todo-indicator bg-focus"></div>
                      <div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                          <div class="widget-content-left mr-2">
                            <div class="custom-checkbox custom-control"><input class="custom-control-input" id="exampleCustomCheckbox1" type="checkbox"><label class="custom-control-label" for="exampleCustomCheckbox1">&nbsp;</label></div>
                          </div>
                          <div class="widget-content-left">
                            <div class="widget-heading">Make payment to Bluedart</div>
                            <div class="widget-subheading">
                              <div>By Johnny <div class="badge badge-pill badge-info ml-2">NEW</div>
                              </div>

                            </div>

                          </div>
                          <div class="widget-content-right">
                            <button class="border-0 btn-transition btn btn-outline-success">
                              <i class="fa fa-check"></i></button>
                            <button class="border-0 btn-transition btn btn-outline-danger">
                              <i class="fa fa-trash"></i>

                            </button>
                          </div>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item">
                      <div class="todo-indicator bg-primary"></div>
                      <div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                          <div class="widget-content-left mr-2">
                            <div class="custom-checkbox custom-control"><input class="custom-control-input" id="exampleCustomCheckbox4" type="checkbox"><label class="custom-control-label" for="exampleCustomCheckbox4">&nbsp;</label></div>
                          </div>
                          <div class="widget-content-left flex2">
                            <div class="widget-heading">Office rent </div>
                            <div class="widget-subheading">By Samino!</div>
                          </div>

                          <div class="widget-content-right">
                            <button class="border-0 btn-transition btn btn-outline-success">
                              <i class="fa fa-check"></i></button>
                            <button class="border-0 btn-transition btn btn-outline-danger">
                              <i class="fa fa-trash"></i>

                            </button>
                          </div>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item">
                      <div class="todo-indicator bg-info"></div>
                      <div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                          <div class="widget-content-left mr-2">
                            <div class="custom-checkbox custom-control"><input class="custom-control-input" id="exampleCustomCheckbox2" type="checkbox"><label class="custom-control-label" for="exampleCustomCheckbox2">&nbsp;</label></div>
                          </div>

                          <div class="widget-content-left">
                            <div class="widget-heading">Office grocery shopping</div>
                            <div class="widget-subheading">By Tida</div>
                          </div>
                          <div class="widget-content-right">
                            <button class="border-0 btn-transition btn btn-outline-success">
                              <i class="fa fa-check"></i></button>
                            <button class="border-0 btn-transition btn btn-outline-danger">
                              <i class="fa fa-trash"></i>

                            </button>
                          </div>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item">
                      <div class="todo-indicator bg-success"></div>
                      <div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                          <div class="widget-content-left mr-2">
                            <div class="custom-checkbox custom-control"><input class="custom-control-input" id="exampleCustomCheckbox3" type="checkbox"><label class="custom-control-label" for="exampleCustomCheckbox3">&nbsp;</label></div>
                          </div>
                          <div class="widget-content-left flex2">
                            <div class="widget-heading">Ask for Lunch to Clients</div>
                            <div class="widget-subheading">By Office Admin</div>
                          </div>

                          <div class="widget-content-right">
                            <button class="border-0 btn-transition btn btn-outline-success">
                              <i class="fa fa-check"></i></button>
                            <button class="border-0 btn-transition btn btn-outline-danger">
                              <i class="fa fa-trash"></i>

                            </button>
                          </div>
                        </div>
                      </div>
                    </li>

                    <li class="list-group-item">
                      <div class="todo-indicator bg-success"></div>
                      <div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                          <div class="widget-content-left mr-2">
                            <div class="custom-checkbox custom-control"><input class="custom-control-input" id="exampleCustomCheckbox10" type="checkbox"><label class="custom-control-label" for="exampleCustomCheckbox10">&nbsp;</label></div>
                          </div>
                          <div class="widget-content-left flex2">
                            <div class="widget-heading">Client Meeting at 11 AM</div>
                            <div class="widget-subheading">By CEO</div>
                          </div>

                          <div class="widget-content-right">
                            <button class="border-0 btn-transition btn btn-outline-success">
                              <i class="fa fa-check"></i></button>
                            <button class="border-0 btn-transition btn btn-outline-danger">
                              <i class="fa fa-trash"></i>

                            </button>
                          </div>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>

              </div>
            </perfect-scrollbar>
          </div>
          <div class="d-block text-right card-footer"><button class="mr-2 btn btn-link btn-sm">Cancel</button><button class="btn btn-primary">Add Task</button></div>
        </div>
      </div>
    </div>

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
    datasets: [{
      data: [700, 500, 400, 600, 300, 100],
      backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
    }]
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
  $('#color-chooser > li > a').click(function(e) {
    e.preventDefault()
    // Save color
    currColor = $(this).css('color')
    // Add color effect to button
    $('#add-new-event').css({
      'background-color': currColor,
      'border-color': currColor
    })
  })
  $('#add-new-event').click(function(e) {
    e.preventDefault()
    // Get value and make sure it is not null
    var val = $('#new-event').val()
    if (val == null || val == "") {
      return
    }

    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      data: {
        todo_name: val,
        action: "add-todo",
        company_id: <?php echo $_SESSION["companyID"]; ?>,
        project_id: $("#projects option:selected").val(),
      },
      success: function(data) {
        var res = JSON.parse(data);
        console.log(res.data);
        $(".todo-list").html(res.data);
      }
    });
    // Remove event from text input
    $('#new-event').val('')

  })
</script>