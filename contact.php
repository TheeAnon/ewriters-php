<?php
session_start();
include_once 'func.php';
if (!isset($_SESSION['e_id'])) {
  if (isset($_COOKIE['rememberme'])) {
    $_SESSION['e_id'] = decryptCookie($_COOKIE['rememberme']);
  }
}
include 'db.php'; 

$title = 'Contact us - Best Online Writing Jobs Marketplace. Get Online Writing Jobs or Hire Writers!';
include 'header.php';
?>
<main id="main" class="main">
 <div class="pagetitle">
    <h1>Contact us</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Contact</li>
      </ol>
    </nav>
  </div>
  
<div class="container">
<form>
  <!-- Name input -->
  <div class="col-12">
    <label class="form-label">Name</label> 
    <input required type="text" name="name" class="form-control" />
  </div></br>

  <!-- Email input -->
  <div class="col-12">
    <label class="form-label">Email address</label> 
    <input required type="email" name="email" class="form-control" />
  </div></br>
  <!-- Email input -->
  <div class="col-12">
    <label class="form-label">Subject</label>
    <input required type="text" name="subject" class="form-control" />
  </div></br>

  <!-- Message input -->
  <div class="col-12">
    <label class="form-label" >Message</label>
    <textarea required class="form-control" name="message" rows="4"></textarea>
  </div></br>

  <!-- Submit button -->
  <button type="submit" class="btn btn-primary btn-block mb-4">Send</button>
</form>
</div>

</main>
<?php
include 'footer.php';
?>