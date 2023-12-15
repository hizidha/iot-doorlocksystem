<?php
    require "./event/connection_db.php";

    session_start();
    if (!isset($_SESSION['username']) || $_SESSION['username'] == 0) {
        header("Location: ?page=portal");
        exit();
    }

    $cardSerialEdit = $_GET['id'];
    $sql = "SELECT * FROM uid_card WHERE id = '$cardSerialEdit'";
    $row = mysqli_fetch_array(mysqli_query($conn, $sql));

    $sqlList = "SELECT id, full_name FROM user WHERE role != 0";
    $listOwners = mysqli_query($conn, $sqlList);
?>

    <title>Edit Card Serial Number | Door Lock System</title>
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
                  <h4 class="text-bold m-0 mt-2">Edit Card Serial Number</h4>
                  <p id="timeLive" class="text-sm mt-1 mb-0"></p>
                </div>
              </div>
              <div class="card-body">
              <form action="?event=cardEdit" method="POST" name="editCardData" id="editCardData">
                <input type="hidden" name="cardHeadEdit" value="<?php echo $row['id']; ?>">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="SerialCardEdit">UID Card</label>
                            <div class="input-group">
                                <input type="text" class="form-control inputMe" required
                                    id="SerialCardEdit" name="SerialCardEdit" placeholder="Card Serial Number"
                                    value="<?php echo $row['serial_number']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="ownercardEdit">Owner</label>
                            <div class="input-group">
                                <select class="form-control" id="ownercardEdit" name="ownercardEdit" required>
                                    <?php
                                    if ($listOwners->num_rows > 0) {
                                        while ($userRow = mysqli_fetch_assoc($listOwners)) {
                                            $selected = ($userRow['id'] == $row['id_owner']) ? 'selected' : '';
                                            echo '<option value="' . $userRow['id'] . '" ' . $selected . '>' . $userRow['full_name'] . '</option>';
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
                </div>
                <div class="modal-footer justify-content-between px-0">
                    <a type="button" class="btn btn-danger btnSet" href="?page=cardManagement">Cancel</a>
                    <button type="submit" class="btn btnMe btnSet">Update Card</button>
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
  </div>
  <?php 
    require "./page/element/footer.php" 
  ?>
