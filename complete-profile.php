<?php
session_start();
include_once 'func.php';
if (!isset($_SESSION['e_id'])) {
  if (!isset($_COOKIE['rememberme'])) {
    echo '<script>window.location.assign("/login")</script>';
    exit;
  } else {
    $_SESSION['e_id'] = decryptCookie($_COOKIE['rememberme']);
  }
}
include 'db.php';
if (null != getProfile($conn, $_SESSION['e_id'])) {
  echo '<script>window.location.assign("/dashboard");</script>';
}
$title = 'Complete your profile. Give it a professional look - Best Online Writing Jobs Marketplace. Get Online Writing Jobs or Hire Writers!';
include 'header.php';
?>
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Complete profile</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Set up profile</li>
      </ol>
    </nav>
  </div>
  <?php
  foreach (getUser($conn, $_SESSION['e_id']) as $row) {}
  foreach (getProfile($conn, $_SESSION['e_id']) as $pro) {}
  ?>
  <div class="container d-flex col-12 col-md-6 col-lg-4">
    <div class="card p-3 col-12">
      <div class="align-items-center d-flex">
        <div class="image" style="padding-right:5px;">
          <img id="preview" src="dp/<?php if (isset($pro)) { echo $pro['dp']; } else { echo 'default.png'; } ?>" width="50" height="50" alt="Profile" class="rounded-circle">
        </div>
        <div class="ml-3 w-100">
          <h6 class="mb-0 mt-0"><?php echo $row['f_name']." ".$row['l_name']; ?><sup style="color:purple;"><?php echo $row['type']; ?></sup></h6>
          <small><?php echo $row['phone']; ?></small>
        </div>
      </div>
    </div>
  </div>

  <div class="text-center">
    <b>Add taste to your profile</b>
  </div>
  <form class="row g-3" method="post" id="complete-profile-form" enctype="multipart/form-data">
    <div class="alert alert-success" style="display:none;" id="successmsg" role="alert"></div>
    <div class="alert alert-danger" style="display:none;" id="errormsg" role="alert"></div>
    <div class="col-12">
      <label class="form-label">Upload profile picture</label>
      <input type="file" id="id_image" name="dp" onchange="previewImage()" accept="image/*">
    </div>
    <div class="col-4">
      <label class="form-label">Experience (Years)</label>
      <input name="experience" type="number" value="<?php if (isset($pro)) { echo $pro['experience']; } ?>" maxlength="2" class="form-control">
      <input name="action" type="hidden" value="complete_profile">
    </div>
    <div class="col-8">
      <label class="form-label">Skills</label>
      <select required class="form-select" multiple aria-label="multiple select example" name="skills[]">
        <?php
        $options = array("Content Writing", "Copywriting", "Academic Writing", "Editing and Proofreading", "Social Media Management", "Graphic Design", "Web Development", "Mobile App Development");
        $db_value = "";
        if (isset($pro)) {
          $db_value = explode(",", $pro['skills']);
          foreach ($options as $option) {
            foreach ($db_value as $val) {
              echo '<option value="' . $option . '"';
              if ($option == $val) {
                echo ' selected';
              }
              echo '>' . $option . '</option>';
            }}}else{
              foreach ($options as $option) {
              echo '<option value="' . $option . '">' . $option . '</option>';
            }}
        ?>

      </select>
    </div>
    <!-- Message input -->
    <div class="col-12">
      <label class="form-label">About</label>
      <textarea class="form-control" name="about" value="<?php if (isset($pro)) { echo $pro['about']; } ?>" rows="4"></textarea>
    </div>
  </br>
  </br>
    <div class="col-12">
      <div id="sign_up">
        <button type="submit" class="btn btn-primary">Done</button>
      </div>
    </div>
  </form>
  <script>
    function previewImage() {
      var preview = document.getElementById("preview");
      var file = document.getElementById("id_image").files[0];
      var reader = new FileReader();
      reader.onloadend = function() {
        preview.src = reader.result;
        preview.style.display = "block";
      }
      if (file) {
        reader.readAsDataURL(file);
      }
    }
  </script>
  <script>
    $(document).ready(function() {
      // submit form
      $("#complete-profile-form").submit(function(e) {
        e.preventDefault();
        $('#sign_up').LoadingOverlay("show");
        var form = document.getElementById("complete-profile-form");
        var formData = new FormData(form);

        $.ajax({
          url: '/func.php',
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            if (response === 'success') {
              $('#complete-profile-form')[0].reset();
              $('#successmsg').text("Your profile is now professional!");
              $('#successmsg').fadeIn();
              $('#errormsg').fadeOut();
              window.location.assign('/dashboard');
            } else {
              $('#sign_up').LoadingOverlay("hide");
              $('#errormsg').text(response);
              $('#errormsg').fadeIn();
              $('#successmsg').fadeOut();
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