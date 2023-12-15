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

  <title>History | Door Lock System</title>
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
            <h3 class="m-0 text-bold">History</h3>
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
    <?php 
      require "././event/connection_db.php";

      $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
      $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

      $sql = "SELECT history.*, user.full_name AS fullName_owner  
              FROM history 
              LEFT JOIN uid_card ON history.id_uidCard = uid_card.id
              LEFT JOIN user ON uid_card.id_owner = user.id ";

      if (!empty($start_date) && !empty($end_date)) {
          $sql .= "WHERE history.timestamp BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59' ";
      }
      $sql .= "ORDER BY history.timestamp DESC";
      $result = mysqli_query($conn, $sql);
    ?>

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                  <form method="POST" action="?page=history" class="d-flex justify-content-between align-items-center">
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
              <div class="card-body">
                <table id="dataManagementTableVer" class="table table-bordered table-striped dataTable dtr-inline">
                  <thead>
                  <tr>
                    <th style="text-align: center;">No</th>
                    <th style="text-align: center;">Card Serial Number</th>
                    <th style="text-align: center;">Owner</th>
                    <th style="text-align: center;">Action</th>
                    <th style="text-align: center;">Timestamp</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php $i=1; while($row = mysqli_fetch_array($result)){ ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $i++ ?></td>
                        <td><?php echo $row['serial_number'] ?></td>
                        <td><?php echo $row['fullName_owner'] ?></td>
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
                  <?php } mysqli_close($conn); ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.card -->
        </div>  
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </div>

  <?php 
    require "./page/element/footer.php" 
  ?>