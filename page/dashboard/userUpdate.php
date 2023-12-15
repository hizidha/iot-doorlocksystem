<?php
    require "./event/connection_db.php";

    session_start();
    if (!isset($_SESSION['username']) || $_SESSION['username'] == 0) {
        header("Location: ?page=portal");
        exit();
    }
  
    $usernameEdit = $_GET['username'];
    $sql = "SELECT * FROM user WHERE username = '$usernameEdit'";
    $row = mysqli_fetch_array(mysqli_query($conn, $sql));
?>

<title>Edit User | Door Lock System</title>
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
  <!-- Main content -->
  <div class="content pt-5 pb-4">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
            <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                  <h4 class="text-bold m-0 mt-2">Edit User Data</h4>
                  <p id="timeLive" class="text-sm mt-1 mb-0"></p>
                </div>
              </div>
              <div class="card-body">
                <form action="?event=userEdit" method="POST" 
                    name="editUserData" id="editUserData">
                  <div class="form-group">
                  <label for="fullNameEditUser">Full Name</label>
                  <div class="input-group">
                      <input type="text" class="form-control inputMe" required
                      id="fullNameEditUser" name="fullNameEditUser" placeholder="Full Name" 
                      value="<?php echo $row['full_name']; ?>">
                  </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-6">
                      <input type="hidden" name="usernameEdit" value="<?php echo $row['username']; ?>">
                      <div class="form-group">
                        <label for="usernameEditUser">Username</label>
                        <div class="input-group">
                          <input type="text" class="form-control inputMe" required disabled
                            id="usernameEditUser" name="usernameEditUser" placeholder="Username" 
                            value="<?php echo $row['username']; ?>">
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="passwordEditUser">Password</label>
                        <div class="input-group">
                          <input type="password" class="form-control inputMe" 
                            id="passwordEditUser" name="passwordEditUser" 
                            placeholder="Leave blank to keep the current password">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label for="divisionEditUser">Division</label>
                        <div class="input-group">
                          <input type="text" class="form-control inputMe" required
                            id="divisionEditUser" name="divisionEditUser" placeholder="Division" 
                            value="<?php echo $row['division']; ?>">
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label for="departmentEditUser">Department</label>
                        <div class="input-group">
                          <input type="text" class="form-control inputMe" required
                            id="departmentEditUser" name="departmentEditUser" placeholder="Department" 
                            value="<?php echo $row['department']; ?>">
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label for="positionEditUser">Position</label>
                        <div class="input-group">
                          <input type="text" class="form-control inputMe" required
                            id="positionEditUser" name="positionEditUser" placeholder="Position" 
                            value="<?php echo $row['position']; ?>">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-4">
                      <div class="form-group">
                          <label for="roleEditUser">Role</label>
                          <select class="form-control" id="roleEditUser" name="roleEditUser" required>
                              <?php
                                  $roleValue = $row['role'];
                                  $selectedRole = '';
                                  
                                  if ($roleValue == 0) {
                                      $selectedRole = 'Super Admin';
                                  } elseif ($roleValue == 1) {
                                      $selectedRole = 'Admin';
                                  } elseif ($roleValue == 2) {
                                      $selectedRole = 'Member';
                                  }
                              ?>
                              <option value="<?php echo $roleValue; ?>" selected><?php echo $selectedRole; ?></option>
                              <?php if($roleValue == 2) { ?>
                              <option value="1">Admin</option>
                              <?php } else if ($roleValue == 1) {?>
                              <option value="2">Member</option>
                              <?php } ?>
                          </select>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label for="createdEditUser">Created at</label>
                        <div class="input-group">
                          <input type="text" class="form-control inputMe" disabled
                            id="createdEditUser" name="createdEditUser" 
                            value="<?php echo $row['created_at']; ?>">
                        </div>  
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label for="lastmodifiedEditUser">Last Modified at</label>
                        <div class="input-group">
                          <input type="text" class="form-control inputMe" disabled
                            id="lastmodifiedEditUser" name="lastmodifiedEditUser" 
                            value="<?php echo $row['lastmodified_at']; ?>">
                        </div>  
                      </div>
                    </div>
                  </div>
                
                  <div class="modal-footer justify-content-between px-0">
                    <a type="button" class="btn btn-danger btnSet"
                        href="?page=userManagement">
                        Cancel
                    </a>
                    <button type="submit" class="btn btnMe btnSet">Update User</button>
                  </div>
                </form>
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
