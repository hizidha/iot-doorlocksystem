<?php 
    session_start();

    if(isset($_SESSION['username'])){
        $idUser = $_SESSION['id'];
    } else {
      header("Location: ?page=portal");
      exit();
    }
?>
  
  <title>Profile | Door Lock System</title>
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
            <h4 class="m-0 text-bold">Profile User</h4>
            <p id="timeLive" class="text-sm mt-1"></p>
          </div>
          <!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
            <div class="card card-primary card-outline">
              <div class="card-body">
                <div class="row mt-2">
                    <div class="col-sm-3 text-center">
                        <img class="profile-user-img img-fluid img-circle"
                        src="./dist/img/blank-profile-picture.jpg" alt="User profile picture">
                    </div>
                    <div class="col-sm-9">
                      <?php
                        require "./event/connection_db.php";
          
                        $sql = "SELECT * FROM user WHERE id = $idUser";
                        $result = mysqli_query($conn, $sql);
                        
                        while($row = mysqli_fetch_assoc($result)){
                            $status = $row['role'];
                            if ($status == 0) {
                            $status = 'Super Admin';
                            } else if ($status == 1) {
                            $status = 'Admin';
                            } else {
                            $status = 'Member';
                            }
                      ?>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="usernameProfile">Username</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control inputMe"
                                        name="usernameProfile" value="<?php echo $row['username'] ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="fullnameProfile">Full Name</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control inputMe"
                                        name="fullnameProfile" value="<?php echo $row['full_name'] ?>" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="departmentProfile">Department</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control inputMe"
                                        name="departmentProfile" value="<?php echo $row['department'] ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="divisionProfile">Division</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control inputMe"
                                        name="divisionProfile" value="<?php echo $row['division'] ?>" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="positionProfile">Position</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control inputMe"
                                        name="positionProfile" value="<?php echo $row['position'] ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="roleProfile">Role</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control inputMe"
                                        name="roleProfile" value="<?php echo $status ?>" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                      <?php } ?>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>  
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </div>
  </div>

  <?php 
    require "./page/element/footer.php" 
  ?>