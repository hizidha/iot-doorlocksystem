  <style>
    .bodyPortal {
    background-image: url('./dist/img/background-img.jpg');
    background-size: cover;
    background-repeat: space;  
  } .bodyPortal::before {
    content: "";
    position: fixed;
    top: 0;
    left: 0;
    z-index: -1;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
  }
  </style>

  <title>Portal | Door Lock System</title>
  <?php 
    session_start();

    if (isset($_SESSION['username']) && empty($_SESSION['logoutMsg'])){
      header("Location: ?page=dashboard");
      exit();
    }
  ?>
</head>

<body class="bodyPortal">
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="./dist/img/logo-sier.png" alt="Logo PT SIER" height="80">
  </div>

  <div class="content-wrap d-flex justify-content-center align-items-center pageCenter">
    <div class="col-md-4 card mx-5">
    <div class="card-body">
      <div class="d-none d-lg-flex flex-column justify-content-center align-items-center p-5 pt-3 pb-4">
        <img src="./dist/img/logo-sier.png" alt="Logo PT SIER" height="70">
        <p class="pt-1 text-xs text-bold">IoT-based Door Lock System</p>
      </div>
      <div class="justify-content-start">
          <h3 class="mb-0 text-bold">LOGIN</h3>
          <p class="text-sm">Sign in with your account</p>
          <div>
            <form action="?event=login" method="POST"> <!-- ./page/dashboard/ -->
              <div class="form-group">
                <label for="usernameLogin">Username</label>
                <div class="input-group">
                  <input type="text" class="form-control inputMe" required
                    id="usernameLogin" name="usernameLogin" placeholder="Username">
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="passwordLogin">Password</label>
                <div class="input-group">
                  <input type="password" class="form-control inputMe" required
                    id="passwordLogin" name="passwordLogin" placeholder="Password">
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                  </div>
                </div>
              </div>

              <div class="my-3 mt-4 d-flex justify-content-end">
                <button type="submit" class="btn btnSet btnMe">Submit</button>
              </div>
            </form>
          </div>
        </div>
    </div></div>
  </div>
  
  <!-- Error Login Toast -->
  <?php if (isset($_SESSION['loginError']) && !empty($_SESSION['loginError'])): ?>
    <script>
      $(document).ready(function() {
        toastr.error('<?php echo $_SESSION['loginError']; ?>', 'Login Error');
          
        <?php 
          unset($_SESSION['loginError']); 
        ?>
      });
    </script>
  <?php endif; ?>

  <!-- Logout Toast -->
  <?php if (isset($_SESSION['logoutMsg']) && !empty($_SESSION['logoutMsg'])): ?>
    <script>
      $(document).ready(function() {
        toastr.success('<?php echo $_SESSION['logoutMsg']; ?>', 'Logout Success');

        <?php 
          unset($_SESSION['logoutMsg']);
          session_unset();
          session_destroy();
        ?>
      });
    </script>
  <?php endif; ?>