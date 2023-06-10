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

$title = 'Sign up with the best Online Writing Jobs Marketplace. Get Online Writing Jobs or Hire Writers!';
include 'header.php';
?>
 
<main id="main" class="main">
<div class="pagetitle">
  <h1>Sign up with us</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item active">Register</li>
    </ol>
  </nav>
</div>

    <form class="row g-3" method="post" id="register-form">
      <div class="alert alert-success" style="display:none;" id="successmsg" role="alert"></div>
      <div class="alert alert-danger" style="display:none;" id="errormsg" role="alert"></div>
      <div class="col-6">
        <label class="form-label">First Name</label>
        <input required name="first_name" type="text" class="form-control">
      </div>
      <div class="col-6">
        <label class="form-label">Last Name</label>
        <input required name="last_name" type="text" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label">Phone</label>
        <input required name="phone" type="number" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input required name="email" type="email" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label">Password</label>
        <input required type="password" minlength="6" class="form-control" name="password">
      </div>
      <div class="col-md-6">
        <label class="form-label">Confirm Password</label>
        <input required type="password" minlength="6" class="form-control" name="password2">
        <input type="hidden" value="register" name="action">
      </div>
      <div class="col-md-4">
        <label for="inputState" class="form-label">Account type</label>
        <select required name="type" id="inputState" class="form-select">
          <option selected value="Freelancer">I'm a Freelancer</option>
          <option value="Employer">I'm an Employer</option>
        </select>
      </div>
    </br>
    </br>
      <div class="col-12">
        <div id="sign_up"><button type="submit" class="btn btn-primary">Sign up</button></div>
      </div>
    </br>
    </br>
      <div class="col-12">
        <p>
          Already have an account? <a href="/login">Sign in</a>
        </p>
      </div>
    </form>


<script>
  $(document).ready(function() {
    // submit form
    $("#register-form").submit(function(e) {
      e.preventDefault();
      $('#sign_up').LoadingOverlay("show");

      $.ajax({
        type: "POST",
        url: "/func.php",
        data: $("#register-form").serialize(),
        success: function(response) {
          if (response === 'success') {
            $('#errormsg').fadeOut();
            $('#register-form')[0].reset();
            $('#successmsg').text("Registeration successful! Logging you in.... ");
            $('#successmsg').fadeIn();
            window.location.assign('/complete-profile')
          } else {
            $('#sign_up').LoadingOverlay("hide");
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