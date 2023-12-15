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

  <title>Dashboard | Door Lock System</title>
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
            <h3 class="m-0 text-bold">Dashboard</h3>
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
    $first_day_of_week = date('Y-m-d', strtotime('last Sunday', strtotime($today)));
    $last_day_of_week = date('Y-m-d', strtotime('next Saturday', strtotime($today)));

    $query1 = "SELECT DAYOFWEEK(history.timestamp) AS day_of_week,
              COUNT(*) AS total_actions, user.full_name AS owners 
              FROM history 
              LEFT JOIN uid_card ON history.id_uidCard = uid_card.id
              LEFT JOIN user ON uid_card.id_owner = user.id
              WHERE history.action_type = 0 
                    AND history.timestamp BETWEEN '$first_day_of_week' AND '$last_day_of_week'
              GROUP BY day_of_week, owners
              ORDER BY day_of_week, owners;";

    $query2 = "SELECT DAYOFWEEK(history.timestamp) AS day_of_week,
              COUNT(*) AS total_actions, user.full_name AS owners 
              FROM history 
              LEFT JOIN uid_card ON history.id_uidCard = uid_card.id
              LEFT JOIN user ON uid_card.id_owner = user.id
              WHERE history.id_uidCard = 5 
                    AND history.timestamp BETWEEN '$first_day_of_week' AND '$last_day_of_week'
              GROUP BY day_of_week
              ORDER BY day_of_week;";

      $query3 = "SELECT DAYOFWEEK(history.timestamp) AS day_of_week, COUNT(*) AS total_actions,
                  CASE 
                    WHEN history.id_uidCard = 6 THEN 'Button Emergency'
                    WHEN history.id_uidCard = 7 THEN 'Button Exit'
                  END AS owners
                FROM history 
                LEFT JOIN uid_card ON history.id_uidCard = uid_card.id
                LEFT JOIN user ON uid_card.id_owner = user.id
                WHERE history.action_type != 0 
                      AND history.timestamp BETWEEN '$first_day_of_week' AND '$last_day_of_week'
                GROUP BY day_of_week, owners
                ORDER BY day_of_week;";

    $result1 = mysqli_query($conn, $query1);
    $result2 = mysqli_query($conn, $query2);
    $result3 = mysqli_query($conn, $query3);

    $dataTappingcard = array();
    $dataGuest = array();
    $dataButton = array();
    
    while ($row1 = $result1->fetch_assoc()) {
      $dataTappingcard[] = $row1;
    }

    while ($row2 = $result2->fetch_assoc()) {
      $dataGuest[] = $row2;
    }

    while ($row3 = $result3->fetch_assoc()) {
      $dataButton[] = $row3;
    }
    
    mysqli_close($conn);
  ?>
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between py-2">
                  <h5 class="text-bold">Tapping RFID Card Activities</h5>
                  <p class="pt-1 text-semibold"><?php echo date('d M Y', strtotime($first_day_of_week)) . ' - ' . date('d M Y', strtotime($last_day_of_week)); ?></p>
                </div>
              </div>
              <div class="card-body pt-0 mb-2">
                <div class="chart-container">
                  <canvas id="chartTapping" class="chart" height="120" width="auto"></canvas>
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
                  <canvas id="chartGuest" class="chart" height="160" width="auto"></canvas>
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
                  <canvas id="chartButton" class="chart" height="160" width="auto"></canvas>
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->

        <div class="row">
          <div class="col-lg-12">
          <div class="card p-2">
              <div class="card-header border-0">
                <div class="py-1 d-flex justify-content-start">
                  <h4 class="text-bold">Recent Activities</h4>
                </div>
              </div>
              <div class="card-body table-responsive pt-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Owner</th>
                      <th>UID Card</th>
                      <th>Action Type</th>
                      <th>Timestamp</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      require "././event/connection_db.php";

                      $sql = "SELECT history.*, user.full_name AS fullName_owner 
                              FROM history 
                              LEFT JOIN uid_card ON history.id_uidCard = uid_card.id 
                              LEFT JOIN user ON uid_card.id_owner = user.id 
                              ORDER BY timestamp DESC 
                              LIMIT 5";
                      $result = mysqli_query($conn, $sql);
                      
                      $i = 0; 
                      while($row = mysqli_fetch_array($result)){ 
                        $i++;
                    ?>
                    <tr>
                      <td><?php echo $i ?></td>
                      <td><?php echo $row['fullName_owner'] ?></td>
                      <td><?php echo $row['serial_number'] ?></td>
                      <td><?php
                            $actionType = $row['action_type'];
                            if ($actionType == 0) {
                                $actionType = 'Tapping RFID Card';
                            } else if ($actionType == 1) {
                                $actionType = 'Button Emergency';
                            } else {
                                $actionType = 'Button Exit';
                            }
                            echo $actionType; ?></td>
                      <td><?php  
                            $timestamp = strtotime($row['timestamp']);
                            echo date('l, d F Y  H:i:s', $timestamp); 
                          ?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
      <!-- /.container-fluid -->
    </div>

  <?php 
    require "./page/element/footer.php" 
  ?>

  <!-- Login Toast -->
  <?php if (isset($_SESSION['loginSuccess']) && !empty($_SESSION['loginSuccess'])): ?>
    <script>
      $(document).ready(function() {
        toastr.success('<?php echo $_SESSION['loginSuccess']; ?>', 'Login successfully');
          
        <?php 
          unset($_SESSION['loginSuccess']); 
        ?>
      });
    </script>
  <?php endif; ?>