<?php
  if(isset($_SESSION['username'])){
    $role_user = $_SESSION['role'];
  } else {
    header("Location: ?page=portal");
  }
?>

<!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fas fa-user"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
          <a type="button" class="dropdown-item dropdown-header pl-3 text-left" 
            href="?page=profile">
              Profile
          </a>   
          <button type="button" class="dropdown-item dropdown-header pl-3 text-left" 
            data-toggle="modal" data-target="#modal-signout">
              Sign Out
          </button>    
        </div>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li> -->
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4"
         style="position: fixed; height: 100%; overflow: hidden;">
    <!-- Brand Logo -->
    <div>
      <a href="?page=dashboard" class="brand-link">
        <img src="./dist/img/logo-sier.png" alt="Logo PT SIER" class="elevation-3 mx-3" height="50" style="opacity: .8">
        <span class="brand-text text-bold text-sm">Door Lock System</span>
      </a>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" style="overflow-y: hidden;">
      <!-- Sidebar Menu -->
      <div class="mt-2">
        <nav>
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-header text-bold pl-0">MENU</li>
            <li class="nav-item">
              <a href="?page=dashboard" class="nav-link main-nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="?page=history" class="nav-link main-nav-link">
                <i class="nav-icon far fa-clock"></i>
                <p>History</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="?page=chartgraph" class="nav-link main-nav-link">
                <i class="nav-icon far fa-chart-bar"></i>
                <p>Data Visualization</p>
              </a>
            </li>
            <?php if($role_user == 0 || $role_user == 1): ?>
              <li class="nav-header text-bold pl-0">Data Management</li>   
              <li class="nav-item">
                <a href="?page=userManagement" class="nav-link main-nav-link">
                  <i class="nav-icon fas fa-users"></i>
                  <p>User Management</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=cardManagement" class="nav-link main-nav-link">
                  <i class="nav-icon fas fa-address-card"></i>
                  <p>Card Management</p>
                </a>
              </li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>
    <!-- /.sidebar-menu -->
    <?php
      if ($role_user != 2) { 
        $roleStyle = 'height: 14rem;'; 
      } else { 
        $roleStyle = 'height: 18.67rem;'; 
      }
    ?>
      <div class="space-sidebar" style="<?php echo $roleStyle; ?>"></div>
    <!-- Sign Out Button -->
      <div class="px-3">
          <button type="button" class="btn btn-block btn-danger" 
            data-toggle="modal" data-target="#modal-signout">
            <i class="fas fa-sign-out-alt mr-2"></i> 
            Sign Out
          </button>
      </div>
    </div>
    <!-- /.sidebar -->
  </aside>

  <div class="modal fade" id="modal-signout">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" 
          aria-label="Close"><span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h6>Are you sure to Sign Out of your account now?</h6>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger btnSet" data-dismiss="modal">Cancel</button>
          <form action="?event=logout" method="POST">
            <button type="submit" class="btn btnMe btnSet">Sign Out</button>
          </form>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
  </div>

  <div class="modal fade" id="modal-emergency">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" 
          aria-label="Close"><span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h6>Are you sure you want to enable Emergency Mode?</h6>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger btnSet" data-dismiss="modal">Cancel</button>
          <form action="?event=emergencySystem" method="POST">
            <button type="submit" class="btn btnMe btnSet">Sure</button>
          </form>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
  </div>

  <script>
    // live update date and time
    function formatDigit(angka) {
        return angka < 10 ? '0' + angka : angka;
    }

    function updateWaktu() {
        var timeLiveElement = document.getElementById('timeLive');
        
        if (timeLiveElement) {
            var now = new Date();
            var hari = now.getDay();
            var tanggal = now.getDate();
            var bulan = now.getMonth() + 1;
            var tahun = now.getFullYear();
            var jam = formatDigit(now.getHours());
            var menit = formatDigit(now.getMinutes());
            var detik = formatDigit(now.getSeconds());

            var dayArray = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            var monthArray = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            var waktuString = dayArray[hari] + ', ' + tanggal + ' ' + monthArray[bulan - 1] + ' ' + tahun + ' ' + jam + ':' + menit + ':' + detik;

            timeLiveElement.innerText = waktuString;
        }
    }

    setInterval(updateWaktu, 1000);
    updateWaktu();
  </script>
