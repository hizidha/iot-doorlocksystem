<?php 
    session_start();

    if(isset($_SESSION['username']) || $_SESSION['role'] != 2){
      $role_user = $_SESSION['role'];
    } else {
      header("Location: ?page=portal");
      exit();
    }
?>
  
  <title>User Management | Door Lock System</title>
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
            <h4 class="m-0 text-bold">Data Management</h4>
            <p id="timeLive" class="text-sm mt-1"></p>
          </div>
          <!-- /.col -->
          <div class="col-sm-2 mt-2">
            <div>
              <button type="button" class="btn btn-block btn-danger" 
                data-toggle="modal" data-target="#modal-emergency">
                <i class="fas fa-exclamation-triangle mr-2"></i> 
                Emergency
              </button>
            </div>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
            <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                  <h3 class="card-title text-bold m-0">User Management</h3>
                </div>
                <div class="ml-auto">
                  <button type="button" class="btn btn-block m-auto" 
                    style="background: #007bff; color: white;"
                    data-toggle="modal" data-target="#modal-addUser">
                    <i class="fas fa-user d-inline"></i>
                    <span class="d-none d-sm-inline ml-2">Add Member</span>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <table id="dataManagementTable" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th style="text-align: center;">No</th>
                    <th style="text-align: center;">Full Name</th>
                    <th style="text-align: center;">Username</th>
                    <th style="text-align: center;">Position</th>
                    <th style="text-align: center;">Role</th>
                    <th style="text-align: center;">
                      <?php 
                        $nameTable = "";
                        if ($role_user == 0) { 
                          $nameTable = "Action"; 
                        } else { 
                          $nameTable = "Detail"; 
                        }
                        echo $nameTable
                      ?>
                    </th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php 
                    require "././event/connection_db.php";
                    $sql = "SELECT * FROM user 
                            WHERE role != 3 AND username != 'system'
                            ORDER BY id ASC";
                    $result = mysqli_query($conn, $sql);

                    $i = 0; 
                    while($row = mysqli_fetch_array($result)){ 
                      $i++;
                      $modalId = "modalDetailUser$i";
                      $modalDelete = "modalDeleteUsers$i";
                  ?>
                    <tr>
                      <td style="text-align: center;"><?php echo $i ?></td>
                      <td><?php echo $row['full_name'] ?></td>
                      <td><?php echo $row['username'] ?></td>
                      <td><?php echo $row['position'] ?></td>
                      <td><?php
                            $statuss = $row['role'];
                            if ($statuss == 0) {
                              $statuss = 'Super Admin';
                            } else if ($statuss == 1) {
                              $statuss = 'Admin';
                            } else {
                              $statuss = 'Member';
                            }
                          ?>
                        <?php echo $statuss; ?>
                      </td>
                      <td style="text-align: center;">
                        <button class="btn" style="background: #172554;"
                          data-toggle="modal" data-target="#<?php echo $modalId; ?>">
                            <span style="color: white; font-size: 15px;" class="fa-icon">
                              <i class="fas fa-search"></i>
                            </span> 
                        </button>
                        <?php if($role_user == 0): ?>
                          <button class="btn" style="background: #007bff;">
                            <a href="?page=userUpdate&username=<?php echo $row['username'] ?>"
                              style="color: white; font-size: 15px;">
                              <span class="fa-icon"><i class="fas fa-pen"></i></span>
                            </a>
                          </button>
                          <button class="btn btn-danger" data-toggle="modal" 
                            data-target="#<?php echo $modalDelete; ?>">
                              <span class="fa-icon"><i class="fas fa-trash"></i></span> 
                          </button>
                        <?php endif; ?>
                      </td>
                    </tr>

                    <div class="modal fade" id="<?php echo $modalDelete; ?>">
                      <div class="modal-dialog">
                        <form action="?event=userDelete" method="POST">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="pt-1">Delete User Data</h5>
                            <button type="button" class="close" data-dismiss="modal" 
                            aria-label="Close"><span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <div class="pb-2">
                              <h6>Are you sure about deleting this user data?</h6>
                            </div>
                            <input type="hidden" name="usernameHeadDel" 
                              value="<?php echo $row['username']; ?>">
                            <div class="row">
                              <div class="col-sm-6">
                                <div class="form-group">
                                  <label for="fullNameDeleteUser">Full Name</label>
                                  <div class="input-group">
                                    <input type="text" class="form-control inputMe" disabled
                                      id="fullNameDeleteUser" name="fullNameDeleteUser"
                                      value="<?php echo $row['full_name'] ?>">
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-6">
                                <div class="form-group">
                                  <label for="usernameDeleteUser">Username</label>
                                  <div class="input-group">
                                    <input type="text" class="form-control inputMe" disabled
                                      id="usernameDeleteUser" name="usernameDeleteUser"
                                      value="<?php echo $row['username'] ?>">
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-8">
                                <div class="form-group">
                                  <label for="positionDeleteUser">Position</label>
                                  <div class="input-group">
                                    <input type="text" class="form-control inputMe" disabled
                                      id="positionDeleteUser" name="positionDeleteUser"
                                      value="<?php echo $row['position'] ?>">
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-4">
                                <div class="form-group">
                                  <label for="roleDeleteUser">Role</label>
                                  <div class="input-group">
                                    <input type="text" class="form-control inputMe" disabled
                                      id="roleDeleteUser" name="roleDeleteUser"
                                      value="<?php echo $statuss; ?>">
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-danger btnSet" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btnMe btnSet">Delete</button>
                          </div>
                          </div>
                        </form>
                        <!-- /.modal-content -->
                      </div>
                    <!-- /.modal-dialog -->
                    </div>

                    <div class="modal fade" id="<?php echo $modalId; ?>">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="pt-1">Detail User</h5>
                            <button type="button" class="close" data-dismiss="modal" 
                            aria-label="Close"><span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                            <div class="modal-body">
                              <div class="form-group">
                                <label for="fullNameDetailUser">Full Name</label>
                                <div class="input-group">
                                  <input type="text" class="form-control inputMe" disabled
                                    id="fullNameDetailUser" name="fullNameDetailUser"
                                    value="<?php echo $row['full_name'] ?>">
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="usernameDetailUser">Username</label>
                                <div class="input-group">
                                  <input type="text" class="form-control inputMe" disabled
                                    id="usernameDetailUser" name="usernameDetailUser"
                                    value="<?php echo $row['username'] ?>">
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-sm-6">
                                  <div class="form-group">
                                    <label for="departmentDetailUser">Department</label>
                                    <div class="input-group">
                                      <input type="text" class="form-control inputMe" disabled
                                        id="departmentDetailUser" name="departmentDetailUser"
                                        value="<?php echo $row['department'] ?>">
                                    </div>
                                  </div>
                                </div>
                                <div class="col-sm-6">
                                  <div class="form-group">
                                    <label for="divisionDetailUser">Division</label>
                                    <div class="input-group">
                                      <input type="text" class="form-control inputMe" disabled
                                        id="divisionDetailUser" name="divisionDetailUser"
                                        value="<?php echo $row['division'] ?>">
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-sm-8">
                                  <div class="form-group">
                                    <label for="positionDetailUser">Position</label>
                                    <div class="input-group">
                                      <input type="text" class="form-control inputMe" disabled
                                        id="positionDetailUser" name="positionDetailUser"
                                        value="<?php echo $row['position'] ?>">
                                    </div>
                                  </div>
                                </div>
                                <div class="col-sm-4">
                                  <div class="form-group">
                                    <label for="roleDetailUser">Role</label>
                                    <div class="input-group">
                                      <input type="text" class="form-control inputMe" disabled
                                        id="roleDetailUser" name="roleDetailUser"
                                        value="<?php echo $statuss; ?>">
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-sm-6">
                                  <div class="form-group">
                                    <label for="createdEditUser">Created at</label>
                                    <div class="input-group">
                                      <input type="text" class="form-control inputMe" disabled
                                        id="createdEditUser" name="createdEditUser" 
                                        value="<?php echo $row['created_at']; ?>">
                                    </div>  
                                  </div>
                                </div>
                                <div class="col-sm-6">
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
                            </div>
                        </div>
                        <!-- /.modal-content -->
                      </div>
                    <!-- /.modal-dialog -->
                    </div>
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

  <div class="modal fade" id="modal-addUser">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="pt-1">Register User Data</h5>
          <button type="button" class="close" data-dismiss="modal" 
          aria-label="Close"><span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="?event=userRegister" method="POST" id="registerUsers">
          <div class="modal-body">
            <div class="form-group">
              <label for="fullNameAddUser">Full Name</label>
              <div class="input-group">
                <input type="text" class="form-control inputMe" required
                  id="fullNameAddUser" name="fullNameAddUser" placeholder="Full Name">
              </div>
            </div>
            <div class="form-group">
              <label for="usernameAddUser">Username</label>
              <div class="input-group">
                <input type="text" class="form-control inputMe" required
                  id="usernameAddUser" name="usernameAddUser" placeholder="Username">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="passwordAddUser">Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control inputMe" required
                      id="passwordAddUser" name="passwordAddUser" placeholder="Password">
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="passwordConAddUser">Confirm Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control inputMe" required
                      id="passwordConAddUser" name="passwordConAddUser" placeholder="Confirm Password">
                  </div>
                </div>
              </div>
              <p id="passwordError" class="text-danger text-sm p-0 m-0 pl-2 mb-2"></p>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="departmentAddUser">Department</label>
                  <div class="input-group">
                    <input type="text" class="form-control inputMe" required
                      id="departmentAddUser" name="departmentAddUser" placeholder="Department">
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="divisionAddUser">Division</label>
                  <div class="input-group">
                    <input type="text" class="form-control inputMe" required
                      id="divisionAddUser" name="divisionAddUser" placeholder="Division">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-8">
                <div class="form-group">
                  <label for="positionAddUser">Position</label>
                  <div class="input-group">
                    <input type="text" class="form-control inputMe" required
                      id="positionAddUser" name="positionAddUser" placeholder="Position">
                  </div>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <label for="roleAddUser">Role</label>
                  <select class="form-control" id="roleAddUser" name="roleAddUser" required>
                    <option selected disabled value="">Role User</option>
                    <option value="1">Admin</option>
                    <option value="2">Member</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger btnSet" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btnMe btnSet" id="btnSubmitAddUser" disabled>Add User</button>
        </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
  </div>

  <!-- Register User Toast -->
  <?php if (isset($_SESSION['msgRegister']) && !empty($_SESSION['msgRegister'])): ?>
    <script>
      $(document).ready(function() {
        toastr.<?php echo $_SESSION['statusRegister'] ?>
        ('<?php echo $_SESSION['msgRegister']; ?>', 
         '<?php echo $_SESSION['titleRegister']; ?>');

        <?php
          unset($_SESSION['msgRegister']); 
          unset($_SESSION['statusRegister']); 
          unset($_SESSION['titleRegister']); 
        ?>
      });
    </script>
  <?php endif; ?>

  <!-- Edit User Toast -->
  <?php if (isset($_SESSION['msgUpdate']) && !empty($_SESSION['msgUpdate'])): ?>
      <script>
          $(document).ready(function () {
              toastr.<?php echo $_SESSION['statusUpdate'] ?>
              ('<?php echo $_SESSION['msgUpdate']; ?>',
                  '<?php echo $_SESSION['titleUpdate']; ?>');

              <?php
              unset($_SESSION['msgUpdate']);
              unset($_SESSION['statusUpdate']);
              unset($_SESSION['titleUpdate']);
              ?>
          });
      </script>
  <?php endif; ?>

 <!-- Delete User Toast -->
 <?php if (isset($_SESSION['msgDelete']) && !empty($_SESSION['msgDelete'])): ?>
    <script>
        $(document).ready(function () {
            toastr.<?php echo $_SESSION['statusDelete'] ?>
            ('<?php echo $_SESSION['msgDelete']; ?>',
                '<?php echo $_SESSION['titleDelete']; ?>');

            <?php
            unset($_SESSION['msgDelete']);
            unset($_SESSION['statusDelete']);
            unset($_SESSION['titleDelete']);
            ?>
        });
    </script>
  <?php endif; ?>
