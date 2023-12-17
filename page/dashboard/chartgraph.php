<?php 
    session_start();
    if(isset($_SESSION['username'])){
      $id_user = $_SESSION['id'];
      $role_user = $_SESSION['role'];
    } else {
      header("Location: ?page=portal");
      exit();
    }
?>  

  <title>Data Visualization | Door Lock System</title>
</head>
<body>
<div class="wrapper">
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="./dist/img/logo-sier.png" alt="Logo PT SIER" height="80">
  </div>
  <?php 
    require "./page/element/navbar-sidebar.php"
  ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mt-3 mb-2">
          <div class="col-sm-10">
            <h3 class="m-0 text-bold">Data Visualization</h3>
            <p id="timeLive" class="text-sm mt-1"></p>
          </div>
          <!-- /.col -->
          <?php if($role_user != 2): ?>
          <div class="col-sm-2 mt-2">
            <div>
              <button type="button" class="btn btn-block btn-danger" 
                data-toggle="modal" data-target="#modal-emergency">
                <i class="fas fa-exclamation-triangle mr-2"></i> 
                Emergency
              </button>
            </div>
          </div><!-- /.col -->
          <?php endif; ?>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
  <?php
    require "././event/connection_db.php";

    $today = date('Y-m-d');
    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d', strtotime('Sunday', strtotime($today)));
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d', strtotime('next Saturday', strtotime($today)));
    
    $queryV1 = "SELECT DAYOFWEEK(history.timestamp) AS day_of_week,
              COUNT(*) AS total_actions, user.full_name AS owners 
              FROM history 
              LEFT JOIN uid_card ON history.id_uidCard = uid_card.id
              LEFT JOIN user ON uid_card.id_owner = user.id
              WHERE history.action_type = 0 
                    AND history.timestamp BETWEEN '$start_date' AND '$end_date'
              GROUP BY day_of_week, owners
              ORDER BY day_of_week, owners;";

    $queryV2 = "SELECT DAYOFWEEK(history.timestamp) AS day_of_week,
              COUNT(*) AS total_actions, user.full_name AS owners 
              FROM history 
              LEFT JOIN uid_card ON history.id_uidCard = uid_card.id
              LEFT JOIN user ON uid_card.id_owner = user.id
              WHERE history.id_uidCard = 1 
                    AND history.timestamp BETWEEN '$start_date' AND '$end_date'
              GROUP BY day_of_week
              ORDER BY day_of_week;";

    $queryV3 = "SELECT DAYOFWEEK(history.timestamp) AS day_of_week, COUNT(*) AS total_actions,
                  CASE 
                    WHEN history.id_uidCard = 2 THEN 'Button Emergency'
                    WHEN history.id_uidCard = 3 THEN 'Button Exit'
                  END AS owners
                FROM history 
                LEFT JOIN uid_card ON history.id_uidCard = uid_card.id
                LEFT JOIN user ON uid_card.id_owner = user.id
                WHERE history.action_type != 0 
                      AND history.timestamp BETWEEN '$start_date' AND '$end_date'
                GROUP BY day_of_week, owners
                ORDER BY day_of_week;";

    $resultV1 = mysqli_query($conn, $queryV1);
    $resultV2 = mysqli_query($conn, $queryV2);
    $resultV3 = mysqli_query($conn, $queryV3);

    $chart1 = array();
    $chart2 = array();
    $chart3 = array();
    
    while ($rowV1 = $resultV1->fetch_assoc()) {
        $chart1[] = $rowV1;
    }

    while ($rowV2 = $resultV2->fetch_assoc()) {
        $chart2[] = $rowV2;
    }

    while ($rowV3 = $resultV3->fetch_assoc()) {
        $chart3[] = $rowV3;
    }

    mysqli_close($conn);
  ?>
      <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body pl-4 pr-4 pt-3 pb-3">
                        <form method="POST" action="?page=chartgraph" class="d-flex justify-content-between align-items-center">
                        <div id="class-right">
                            <div class="inline mr-auto" style="display: flex; align-items: center;">
                                <div class="form-group">
                                    <label for="start_date" class="mb-1">Start Date:</label>
                                    <div class="input-group">
                                        <input type="date" name="start_date" id="start_date" class="form-control inputMe"
                                            value="<?php echo htmlspecialchars($start_date); ?>">
                                    </div>
                                </div>
                                <div class="mx-2 mt-4">
                                    <h3 class="mt-1">-</h3>
                                </div>
                                <div class="form-group">
                                    <label for="end_date" class="mb-1">End Date:</label>
                                    <div class="input-group">
                                        <input type="date" name="end_date" id="end_date" class="form-control inputMe"
                                            value="<?php echo htmlspecialchars($end_date); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <div id="class-left" class="mt-1 ml-auto">
                            <a type="button" class="btn btn-danger d-none d-lg-inline px-4" 
                                style="color: white; margin-right: 5px;" href="?page=history">
                                Reset
                            </a>
                            <button type="submit" class="btn btn-primary mx-auto">
                                <i class="fas fa-search d-inline"></i>
                                <span class="d-none d-sm-inline ml-2">Search</span>
                            </button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between py-2">
                  <h5 class="text-bold">Tapping RFID Card Activities</h5>
                </div>
              </div>
              <div class="card-body pt-0 mb-2">
                <div class="chart-container">
                  <canvas id="chart1_visual" class="chart" height="120" width="auto"></canvas>
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col-md-12 -->
        </div>
        <!-- /.row -->

        <div class="row">
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header border-0">
                <div class="p-0 py-1 text-bold">
                  <h6 class="text-bold">Guest Activities</h6>
                </div>
              </div>
              <div class="card-body pt-0">
                <div class="chart-container">
                  <canvas id="chart2_visual" class="chart" height="160" width="auto"></canvas>
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col-md-6 -->

          <div class="col-lg-6">
            <div class="card">
              <div class="card-header border-0">
                <div class="p-0 py-1 text-bold">
                  <h6 class="text-bold">Button Activities</h6>
                </div>
              </div>
              <div class="card-body pt-0">
                <div class="chart-container">
                  <canvas id="chart3_visual" class="chart" height="160" width="auto"></canvas>
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>

  <?php 
    require "./page/element/footer.php" 
  ?>

<script>
    var jsonChart1 = <?php echo json_encode($chart1); ?>;
    var jsonChart2 = <?php echo json_encode($chart2); ?>;
    var jsonChart3 = <?php echo json_encode($chart3); ?>;

    var labels = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    var datasetsChart1 = processData(jsonChart1);
    var ctxChart1 = document.getElementById('chart1_visual').getContext('2d');
    var chart1 = new Chart(ctxChart1, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasetsChart1
        },
        options: {
          scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Day of Week'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total Actions'
                    }
                }
            }
        }
    });

    var datasetsChart2 = processData(jsonChart2);
    var ctxChart2 = document.getElementById('chart2_visual').getContext('2d');
    var chart2 = new Chart(ctxChart2, {
        type: 'line',
        data: {
            labels: labels,
            datasets: datasetsChart2
        },
        options: {
          scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Day of Week'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total Actions'
                    }
                }
            }
        }
    });

    var datasetsChart3 = processData(jsonChart3);
    var ctxChart3 = document.getElementById('chart3_visual').getContext('2d');
    var chart3 = new Chart(ctxChart3, {
        type: 'line',
        data: {
            labels: labels,
            datasets: datasetsChart3
        },
        options: {
          scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Day of Week'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total Actions'
                    }
                }
            }
        }
    });

    function processData(data) {
        var datasets = [];
        var ownersData = {};

        data.forEach(function (entry) {
            var owner = entry.owners;
            if (!ownersData[owner]) {
                ownersData[owner] = {
                    label: owner,
                    data: Array(labels.length).fill(0),
                    backgroundColor: 'rgba(' + Math.floor(Math.random() * 256) + ',' + Math.floor(Math.random() * 256) + ',' + Math.floor(Math.random() * 256) + ',0.5)'
                };
            }
            ownersData[owner].data[parseInt(entry.day_of_week) - 1] += parseInt(entry.total_actions);
        });

        Object.keys(ownersData).forEach(function (owner) {
            datasets.push(ownersData[owner]);
        });

        return datasets;
    }
  </script>