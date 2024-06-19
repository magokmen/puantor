<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/include/requires.php";
$company_id = $_SESSION["companyID"];

$sql = $con->prepare("SELECT COUNT(*) as total_person FROM person WHERE company_id = ?");
$sql->execute(array($company_id));
$result = $sql->fetch(PDO::FETCH_ASSOC);
$totalPersonnel = $result['total_person'];



?>

<!-- ÖZETLER -->
<div class="row">
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Personel Sayısı</span>
        <span class="info-box-number">
          <?php echo $totalPersonnel; ?>
          <small> Personel</small>
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
<!-- ÖZETLER -->

<style>
  .todo-list-item {
    /* position: relative; */
    display: block;
    padding: 0.75rem 1.25rem;
    margin-bottom: -1px;
    background: transparent !important;
    border: none !important;

  }

  .todo-list>li {
    background: transparent !important;
    border: none !important;
  }

  .widget-content-wrapper {
    /* display: flex; */
    /* flex: 1; */
    /* position: relative; */
    align-items: center;
    background: transparent !important;
  }

  .todo-indicator {
    /* position: absolute; */
    width: 4px;
    height: 60%;
    border-radius: 3rem;
    left: 0.625rem;
    top: 70%;
    opacity: .1;
    transition: opacity .2s;
  }

  /* .bg-warning {
    background-color: #f7b924 !important;
} */

  .widget-content {
    padding: 0.2rem;
    flex-direction: row;
    align-items: center;

  }

  .widget-content .widget-content-wrapper {
    display: flex;
    /* flex: 1; */
    /* position: relative; */
    /* align-items: center; */
  }

  .widget-content .widget-content-right.widget-content-actions {
    visibility: hidden;
    opacity: 0;
    transition: opacity .2s;
  }

  .widget-content .widget-content-right {
    margin-left: auto;
  }

  .widget-heading {
    font-size: 16px;
    font-weight: 900;
    color: #3c3c3c;
  }

  .widget-subheading {
    color: #858a8e;
    font-size: 14px;
  }
</style>


<div class="row">
  <!-- TABLE: LATEST ORDERS -->
  <div class="col-md-8">

    <div class="card">
      <div class="card-header">
        <div class="row">

          <div class="col-md-6">
            <?php echo $func->projects("projects", $_SESSION["companyID"], ""); ?>
          </div>
          <div class="col-md-6">

            <div class="input-group mb-3">

              <input type="text" id="new-event" class="form-control" placeholder="Projelere yapılacak ekle">

              <div class="input-group-append">
                <button type="button" id="add-new-event" class="btn btn-primary"><i class="fas fa-plus text-bold"></i> Ekle</button>
              </div>
            </div>
          </div>
          <!-- /btn-group -->
        </div>



      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <ul class="todo-list" data-widget="todo-list">

          <?php

          $sql = $con->prepare("SELECT * FROM todos where company_id = ? ORDER BY id DESC LIMIT 0,5");
          $sql->execute(array($company_id));
          $todos = $sql->fetchAll(PDO::FETCH_OBJ);
          $i = 0;
          foreach ($todos as $todo) : ?>
            <li>
              <div class="widget-content">

                <div class="widget-content-wrapper">

                  <!-- drag handle -->
                  <span class="handle">
                    <i class="fas fa-ellipsis-v"></i>
                    <i class="fas fa-ellipsis-v"></i>
                  </span>
                  <!-- checkbox -->
                  <div class="icheck-primary d-inline ml-2">
                    <input type="checkbox" value="" name="todo<?php echo $i ?>" id="todoCheck<?php echo $i ?>">
                    <label for="todoCheck<?php echo $i ?>"></label>
                  </div>


                  <div class="widget-content-left ml-2">
                    <div class="widget-heading"><?php echo $todo->todo_name; ?></div>
                    <div class="widget-subheading">
                      <div><?php echo $func->getProjectName($todo->project_id); ?> <div class="badge badge-pill badge-info ml-2">Yeni</div>
                      </div>
                    </div>
                  </div>
                  <!-- todo text -->

                  <?php

                  // Calculate the time elapsed since the registration date
                  $registrationDate = strtotime($todo->created_at);
                  $currentDate = time();
                  $timeElapsed =  $currentDate - $registrationDate ;

                  // Convert the time elapsed to hours and minutes
                  $hoursElapsed = floor($timeElapsed / 3600);
                  $minutesElapsed = round(($timeElapsed % 3600) / 60);

                  // Format the time elapsed
                  $timeElapsedFormatted = $hoursElapsed . " saat " . $minutesElapsed . " dakika";

                  // Display the time elapsed
                  echo '<small class="badge badge-danger"><i class="far fa-clock"></i> ' . $timeElapsedFormatted . '</small>';

                  ?>
                  <!-- General tools such as edit or delete-->
                  <div class="tools widget-content-right">
                    <i class="fas fa-edit info edit-todo" data-id="<?php echo $todo->id; ?>"></i>
                    <i class="fas fa-trash danger del-todo" data-id="<?php echo $todo->id; ?>"></i>
                  </div>
                </div>
              </div>
            </li>

          <?php $i++;
          endforeach; ?>

        </ul>
      </div>
      <!-- /.card-body -->
      <div class="card-footer">
        <h3 class="card-title">
          <i class="ion ion-clipboard mr-1"></i>
          Yapılacaklar Listesi
        </h3>
        <div class="card-tools">
          <ul class="pagination pagination-sm float-right">
            <li class="page-item"><a href="#" class="page-link">&laquo;</a></li>
            <?php
            // Get the total number of records in the todos table
            $sql = $con->prepare("SELECT COUNT(*) as total FROM todos where company_id = ?");
            $sql->execute(array($company_id));
            $result = $sql->fetch(PDO::FETCH_ASSOC);
            $totalRecords = $result['total'];

            // Calculate the number of pages
            $perPage = 5;
            $totalPages = ceil($totalRecords / $perPage);

            // Display the pagination links
            for ($i = 1; $i <= $totalPages; $i++) {
              echo '<li class="page-item"><a href="#" class="page-link">' . $i . '</a></li>';
            }
            ?>
            <li class="page-item"><a href="#" class="page-link">&raquo;</a></li>
          </ul>
        </div>

      </div>
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
        action: "todos",
        action_type: "add-todo",
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

  $('.page-item').click(function(e) {
    e.preventDefault();
    var page = $(this).text();

    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      data: {
        page: page,
        action: "todos",
        action_type: "get-todos",
      },
      success: function(data) {
        var res = JSON.parse(data);
        console.log(res.data);
        $(".todo-list").html(res.data);
      }
    });
  });


  $(document).on("click", ".del-todo", function(e) {
    e.preventDefault();
    var todo_id = $(this).attr("data-id");

    swal.fire({
      title: "Emin misiniz?",
      text: "Bu işlemi geri alamazsınız!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Evet, sil!",
      cancelButtonText: "İptal"
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: 'ajax.php',
          type: 'POST',
          data: {
            todo_id: todo_id,
            action: "todos",
            company_id: <?php echo $_SESSION["companyID"]; ?>,
            action_type: "delete-todo",
          },
          success: function(data) {
            var res = JSON.parse(data);
            $(".todo-list").html(res.data);
          }
        });
      }
    });


  });

  $(".select2").select2();
</script>