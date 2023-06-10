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
$title = 'Purchase bids to claim jobs. Best Online Writing Jobs Marketplace. Get Online Writing Jobs or Hire Writers!';
include 'header.php';
?>
<main id="main" class="main">


<div class="pagetitle">
  <h1>My profile</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
      <li class="breadcrumb-item active">My profile</li>
    </ol>
  </nav>
</div>


<div class="row gx-5" id="profile">
  <?php foreach(getProfile($conn, $_SESSION['e_id']) as $row) {
foreach(getUser($conn, $_SESSION['e_id']) as $user) {?>
  <div class="container d-flex col-12 col-md-6 col-lg-4">
    <div class="card p-3 col-12">
      <div class="align-items-center d-flex">
        <div class="image" style="padding-right:5px;">
          <img src="dp/<?php echo getDp($conn, $_SESSION['e_id']);?>" width="100" height="100" alt="Profile" class="rounded-circle">
        </div>
        <div class="ml-3 w-100">
          <h6 class="mb-0 mt-0"><?php echo getNames($conn, $_SESSION['e_id']);?><sup style="color:purple;"><?php echo getAccType($conn, $_SESSION['e_id']);?> </sup></h6>
          <span class="rate-it" data-score="<?php echo getRating($conn, $_SESSION['e_id']);?>"></span>
          <small><?php echo $user['phone'];?></small></br>
          <small><?php echo $row['skills'];?></small>
          <div class="p-2 mt-2 bg-primary d-flex rounded text-white stats">
            <div class="d-flex flex-column">
              <small class="articles">Projects</small>
              <small class="number1"><?php echo getProjectsDone($conn, $_SESSION['e_id'])->rowCount(); ?></small>
            </div>
            <div class="vr" style="margin:2px;"></div>
            <div class="d-flex flex-column">
              <small class="rating">Rating</small>
              <small class="number3"><i class="bi bi-star"></i> <?php echo getRating($conn, $_SESSION['e_id']);?><sub>/5</sub></small>
            </div>
            <div class="vr" style="margin:2px;"></div>
            <div class="d-flex flex-column">
              <small class="rating">Experience</small>
              <small class="number3"><?php echo $row['experience'];?> <sub>Years</sub></small>
            </div>
            <div class="vr" style="margin:2px;"></div>
            <div class="d-flex flex-column">
              <small class="rating">Total Earnings</small>
              <small class="number3">Ksh <?php echo number_format(getTotalEarnings($conn, $_SESSION['e_id']));?></small>
            </div>
          </div>
        </div>
      </div>
      <?php if($row['about'] != '') {?>
      </br>
      <div class="container">
        <h2>About</h2>
        <p><?php echo $row['about'];?></p>
      </div>
      <?php } ?>
      <div class="button mt-2" style="display:none;">
        <button class="btn btn-sm btn-outline-primary" onclick="editProfile()">Edit profile</button>
      </div>
    </div>
  </div>
 <?php }} ?>


  <div class="container d-flex col-12 col-md-6 col-lg-4">
    <div class="card p-3">
      <div class="text-center">
        <h2>Affiliate program</h2>
      </div>
      <div class="container">
        <p>
          Earn by using your referral link to invite new members to our platform.
        </p>
        <b>Invite both employers and new recruits</b></br>
        <span>Your referral link : <a href="https://ewriters.co.ke/register?a=<?php echo $_SESSION['e_id'];?>">https://ewriters.co.ke/register?a=<?php echo $_SESSION['e_id'];?></a></span>
      </br>
      </br>
        <i>Email admin@ewriters.co.ke for more information</i>
      </div>
    </div>
  </div>
</div>


</main>
<?php
include "footer.php";
?>
</body>
</html>