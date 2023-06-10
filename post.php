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

$title = 'Post a project - Get freelancers to work on your projects on the Best Online Writing Jobs Marketplace. Get Online Writing Jobs or Hire Writers!';
include 'header.php';
include 'db.php';
?>
<main id="main" class="main">
  

  <div class="pagetitle">
    <h1>Post a project</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
        <li class="breadcrumb-item active">Post</li>
      </ol>
    </nav>
  </div>
  
  <div class="container p-3 mb-5 bg-body-tertiary rounded" style="padding:20px; margin-top:25px;">
    <form method="post" id="post-form">
      <div class="alert alert-danger" id="errormsg" style="display:none;" role="alert">
        <p id="errormsgp"></p>
        <a href="/deposit" class='btn btn-primary' >Deposit funds</a>
        <button class='btn btn-primary' id="draft-button">Save to drafts</button>
      </div>
      <div class="alert alert-success" id="successmsg" style="display:none;" role="alert"></div>
      <div class="mb-3">
        <label class="form-label">Title</label>
        <input required type="text" class="form-control" name="title">
      </div>
      <div class="mb-3">
        <label class="form-label">Budget (Ksh) </label>
        <input required type='number' class="form-control" name="budget">
        <input type='hidden' value="postproject" name="action">
      </div>
      <div class="col-12">
        <label class="form-label">Description</label>
        <textarea required class="form-control" name="description" rows="4"></textarea>
      </div>
    </br>
      <div class="col-md-4">
        <label for="inputState" class="form-label">Project type</label>
        <select required name="category" id="inputState" class="form-select">
          <option selected value="article_writing">Article writing</option>
          <option value="Academic writing">Academic writing</option>
          <option value="Content creation">Content creation</option>
          <option value="Web design">Web design</option>
          <option value="Blogging">Blogging</option>
          <option value="Review">Review</option>
        </select>
      </div>
    </br></br>
      <div id="submit_btn">
        <button type="submit" class="btn btn-primary">Post</button>
      </div>
    </br>
    </br>
      <p>
        Don't have enough funds? <a href="/deposit">Deposit</a>
      </p>
    </form>
  </div>
</section>




<script>
  $(document).ready(function() {
    // submit form
    $("#post-form").submit(function(e) {
      e.preventDefault();
      $('#submit_btn').LoadingOverlay("show");

      $.ajax({
        type: "POST",
        url: "/func.php",
        data: $("#post-form").serialize(),
        success: function(response) {
          if (response === 'success') {
            $('#post-form')[0].reset();
            $('#successmsg').text("Project posted successfully! The budget has been deducted from your account balance and will be held safe until the project is completed or cancelled.");
            $('#successmsg').fadeIn();
            $('#errormsg').fadeOut();
            $('#submit_btn').LoadingOverlay("hide");
          } else {
            if (response === 'draft') {
              $('#post-form')[0].reset();
              $('#successmsg').text("Project has been saved to draft. You can post it latter after topping up your account.");
              $('#successmsg').fadeIn();
              $('#errormsg').fadeOut();
              $('#submit_btn').LoadingOverlay("hide");
            }
            if (response === 'low funds') {
              $('#submit_btn').LoadingOverlay("hide");
              $('#errormsgp').text("You do not have enough funds to post this project. Make deposit to continue.");
              $('#errormsg').fadeIn();
              $('#successmsg').fadeOut();
            }
          }
        }
      });
    });

    $('#draft-button').click(function() {
      $('<input>').attr({
        type: 'hidden',
        name: 'draft',
        value: 'draft',
      }).appendTo('form');
      $("#login-form").submit();
    });


  });
</script>


</main>
<?php
include 'footer.php';
?>