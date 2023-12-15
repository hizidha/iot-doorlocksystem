<?php 
    session_start();

    if(isset($_SESSION['username']) || $_SESSION['role'] != 2){
      $role_user = $_SESSION['role'];
    } else {
      header("Location: ?page=portal");
      exit();
    }
?>
  
  <title>Card Management | Door Lock System</title>
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
                  <h3 class="card-title text-bold m-0">Card Management</h3>
                </div>
                <div class="ml-auto">
                  <button type="button" class="btn btn-block m-auto" 
                    style="background: #007bff; color: white;"
                    data-toggle="modal" data-target="#modal-addCard">
                    <i class="fas fa-credit-card d-inline"></i>
                    <span class="d-none d-sm-inline ml-2">Add Card</span>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <table id="dataManagementTable" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th style="text-align: center;">No</th>
                    <th style="text-align: center;">Card Serial</th>
                    <th style="text-align: center;">Owner</th>
                    <th style="text-align: center;">Last Modified at</th>
                    <?php if($role_user == 0): ?>
                      <th style="text-align: center;">Action</th>
                    <?php endif; ?>
                  </tr>
                  </thead>
                  <tbody>
                  <?php 
                    require "./event/connection_db.php";
                    $sql = "SELECT uid_card.*, user.full_name
                            FROM uid_card INNER JOIN user 
                            ON uid_card.id_owner = user.id";
                    $result = mysqli_query($conn, $sql);

                    $i = 0; 
                    while($row = mysqli_fetch_assoc($result)){ 
                      $i++;
                      $modalDelete = "modalDeleteUsers$i";
                  ?>
                    <tr>
                      <td style="text-align: center;"><?php echo $i ?></td>
                      <td><?php echo $row['serial_number'] ?></td>
                      <td><?php echo $row['full_name'] ?></td>
                      <td><?php echo $row['created_at'] ?></td>
                      <?php if($role_user == 0): ?>
                        <td style="text-align: center;">
                          <button class="btn" style="background: #007bff;">
                            <a href="?page=cardUpdate&id=<?php echo $row['id'] ?>"
                              style="color: white; font-size: 15px;">
                              <span class="fa-icon"><i class="fas fa-pen"></i></span>
                            </a>
                          </button>
                          <button class="btn btn-danger" data-toggle="modal" 
                            data-target="#<?php echo $modalDelete; ?>">
                              <span class="fa-icon"><i class="fas fa-trash"></i></span> 
                          </button>
                        </td>
                      <?php endif; ?>
                    </tr>

                    <div class="modal fade" id="<?php echo $modalDelete; ?>">
                      <div class="modal-dialog">
                        <form action="?event=cardDelete" method="POST">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="pt-1">Delete Card Data</h5>
                            <button type="button" class="close" data-dismiss="modal" 
                            aria-label="Close"><span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <div class="pb-2">
                              <h6>Are you sure about deleting this card data?</h6>
                            </div>
                            <input type="hidden" name="uidcardHeadDel" 
                              value="<?php echo $row['serial_number']; ?>">
                            <div class="form-group">
                              <label for="uidcardDelete">Card Serial</label>
                              <div class="input-group">
                                <input type="text" class="form-control inputMe" disabled
                                  id="uidcardDelete" name="uidcardDelete"
                                  value="<?php echo $row['serial_number'] ?>">
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-6">
                                <div class="form-group">
                                  <label for="ownerDeleteCard">Owner</label>
                                  <div class="input-group">
                                    <input type="text" class="form-control inputMe" disabled
                                      id="ownerDeleteCard" name="ownerDeleteCard"
                                      value="<?php echo $row['full_name'] ?>">
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-6">
                                <div class="form-group">
                                  <label for="createdDeleteCard">Created at</label>
                                  <div class="input-group">
                                    <input type="text" class="form-control inputMe" disabled
                                      id="createdDeleteCard" name="createdDeleteCard"
                                      value="<?php echo $row['created_at'] ?>">
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
                    <?php } mysqli_close($conn) ?>
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

  <div class="modal fade" id="modal-addCard">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="pt-1">Register Card Data</h5>
          <button type="button" class="close" data-dismiss="modal" 
          aria-label="Close"><span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="?event=cardRegister" method="POST" id="registerCards">
          <div class="modal-body">
            <div class="form-group">
              <label for="SerialCardAdd">UID Card</label>
              <div class="input-group">
                <input type="text" class="form-control inputMe" required
                  id="SerialCardAdd" name="SerialCardAdd" placeholder="Card Serial Number">
              </div>
            </div>
            <div class="form-group">
              <label for="ownercardAdd">Owner</label>
              <div class="input-group">
                <select class="form-control" id="ownercardAdd" name="ownercardAdd" required>
                  <option selected disabled value="">Owner Card</option>
                <?php
                  require "./event/connection_db.php";

                  $sql = "SELECT id, full_name FROM user WHERE role != 0 AND role != 3";
                  $result = mysqli_query($conn, $sql);

                  if ($result && $result->num_rows > 0) {
                      while ($row = mysqli_fetch_assoc($result)) {
                          echo '<option value="' . $row['id'] . '">' . $row['full_name'] . '</option>';
                      }
                  } else {
                      echo '<option value="" disabled>No Users Found</option>';
                  }

                  mysqli_close($conn);
                ?>
                </select>
              </div>
            </div>
          </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger btnSet" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btnMe btnSet" id="btnSubmitCardUser">Add Card</button>
        </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
  </div>

  <!-- Register Card Toast -->
  <?php if (isset($_SESSION['msgRegister1']) && !empty($_SESSION['msgRegister1'])): ?>
    <script>
      $(document).ready(function() {
        toastr.<?php echo $_SESSION['statusRegister1'] ?>
        ('<?php echo $_SESSION['msgRegister1']; ?>', 
         '<?php echo $_SESSION['titleRegister1']; ?>');

        <?php
          unset($_SESSION['msgRegister1']); 
          unset($_SESSION['statusRegister1']); 
          unset($_SESSION['titleRegister1']); 
        ?>
      });
    </script>
  <?php endif; ?>

  <!-- Edit User Toast -->
  <?php if (isset($_SESSION['msgUpdate1']) && !empty($_SESSION['msgUpdate1'])): ?>
      <script>
          $(document).ready(function () {
              toastr.<?php echo $_SESSION['statusUpdate1'] ?>
              ('<?php echo $_SESSION['msgUpdate1']; ?>',
                  '<?php echo $_SESSION['titleUpdate1']; ?>');

              <?php
              unset($_SESSION['msgUpdate1']);
              unset($_SESSION['statusUpdate1']);
              unset($_SESSION['titleUpdate1']);
              ?>
          });
      </script>
  <?php endif; ?>

 <!-- Delete User Toast -->
 <?php if (isset($_SESSION['msgDelete1']) && !empty($_SESSION['msgDelete1'])): ?>
    <script>
        $(document).ready(function () {
            toastr.<?php echo $_SESSION['statusDelete1'] ?>
            ('<?php echo $_SESSION['msgDelete1']; ?>',
                '<?php echo $_SESSION['titleDelete1']; ?>');

            <?php
            unset($_SESSION['msgDelete1']);
            unset($_SESSION['statusDelete1']);
            unset($_SESSION['titleDelete1']);
            ?>
        });
    </script>
  <?php endif; ?>