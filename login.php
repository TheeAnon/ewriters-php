<?php 
session_start();
include 'func.php';
if(isset($_SESSION['e_id'])){
  echo '<script>window.location.assign("/dashboard")</script>';
  exit;
}else if(isset($_COOKIE['rememberme'])){
  $_SESSION['e_id'] = decryptCookie($_COOKIE['rememberme']);
  echo '<script>window.location.assign("/dashboard")</script>';
  exit;
}

$title = 'Login - Sign in to the best Online Writing Jobs Marketplace. Get Online Writing Jobs or Hire Writers!';
include 'header.php';
?>

<main id="main" class="main">
<div class="pagetitle">
  <h1>Sign in</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item active">Login</li>
    </ol>
  </nav>
</div>

    <form method="post" id="login-form">
      <div class="alert alert-danger" id="errormsg" style="display:none;" role="alert"></div>
      <div class="alert alert-success" id="successmsg" style="display:none;" role="alert"></div>
      <div class="mb-3">
        <label class="form-label">Email address</label>
        <input required type="email" class="form-control" name="email">
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input required type="password" class="form-control" name="password">
        <input type="hidden" value="login" name="action">
      </div>
      <div class="mb-3 form-check">
        <input checked type="checkbox" class="form-check-input" name="rememberme">
        <label checkeclass="form-check-label">Remember me</label>
      </div>
      <div id="sign_in"><button type="submit" class="btn btn-primary">Login</button></div>
    </br>
    </br>
      <p>
        Forgot password? <a href="/reset">Reset password</a>
      </p>
      <p>
        Don't have an account? <a href="/register">Sign up</a>
      </p>
    </form>


<script>
  $(document).ready(function() {
    // submit form
    $("#login-form").submit(function(e) {
      e.preventDefault();
      $('#sign_in').LoadingOverlay("show");

      $.ajax({
        type: "POST",
        url: "/func.php",
        data: $("#login-form").serialize(),
        success: function(response) {
          if (response === 'success') {
            $('#errormsg').fadeOut();
            $('#login-form')[0].reset();
            $('#successmsg').text("Authentication successful! Logging you in.... ");
            $('#successmsg').fadeIn();
            window.location.assign('/dashboard');
          } else {
            $('#sign_in').LoadingOverlay("hide");
            $('#errormsg').text(response);
            $('#errormsg').fadeIn();
          }
        }
      });
    });


  });
</script>
</main>
<?php 
include 'footer.php';
?>
