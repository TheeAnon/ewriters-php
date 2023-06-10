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

if (getAccType($conn, $_SESSION['e_id']) != "Freelancer") {
  echo '<script>window.location.assign("/dashboard")</script>';
  exit;
}
?>
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Purchase bids to claim jobs</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Purchase bids</li>
      </ol>
    </nav>
  </div>

  <div class="col-12 col-md-6">
    <div class="card info-card sales-card">

      <div class="filter">
        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
          <li><a class="dropdown-item" href="/gigs">Find Gigs</a></li>
          <li><a class="dropdown-item" href="/deposit">Deposit</a></li>
        </ul>
      </div>

      <div class="card-body">
        <h5 class="card-title">Balance</span></h5>
      <div class="d-flex" style="padding-left:0;">
        <div class="ps-3" style="padding-left:0;">
          <h6>Ksh <?php echo number_format(getBalance($conn, $_SESSION['e_id'])); ?></h6>
          <b><?php echo number_format(getBids($conn, $_SESSION['e_id'])); ?> bids</b>
        </div>
      </div>
    </div>

  </div>
</div>
<?php
if (isset($_GET['p'])) {
  switch ($_GET['p']) {
    case 1:
      $package = 'Starter Package';
      $amount = 200;
      $bids = 10;
      break;
    case 2:
      $package = 'Freelancer plus';
      $amount = 350;
      $bids = 20;
      break;
    case 3:
      $package = 'Freelancer Active';
      $amount = 500;
      $bids = 35;
      break;
    case 4:
      $package = 'Agency';
      $amount = 750;
      $bids = 50;
      break;
  }
  ?>

  <div class="col-12 col-md-6">
    <div class="card info-card sales-card">

      <div class="card-body">
        <div class="alert alert-success" style="display:none;" id="successmsg" role="alert"></div>
        <div class="alert alert-danger" style="display:none;" id="errormsg" role="alert"></div>
        <h5 class="card-title">Make purchase : <?php echo $package; ?></span></h5>
      <div class="d-flex" style="padding-left:0;">
        <div class="ps-3" style="padding-left:0;">
          <h6>Ksh <?php echo number_format($amount); ?></h6>
          <b><?php echo $bids; ?> bids</b>
        </div>
        <div id="pbtn" style="display:inline-block;">
          <button onclick="buyBids()" class="btn btn-primary">Buy</button>
          <a href="/purchase" class="btn btn-outline-dark">Cancel</a>
        </div>

      </div>
    </div>

  </div>
</div>

<script>
  function buyBids() {
    $('#pbtn').LoadingOverlay("show");
    var data = {
      action: 'buybids',
      amount: '<?php echo $amount ?>',
  };

  $.ajax({
    type: "POST",
    url: "/func.php",
    data: data,
    success: function(response) {
      if (response === 'success') {
        $('#successmsg').text("Purchase successful!");
        $('#successmsg').fadeIn();
        $('#errormsg').fadeOut();
        window.location.assign('/purchase');
      } else {
        $('#pbtn').LoadingOverlay("hide");
        $('#errormsg').text(response);
        $('#errormsg').fadeIn();
        $('#successmsg').fadeOut();
      }
    },
    error: function(xhr, status, error) {
      $('#pbtn').LoadingOverlay("hide");
      $('#errormsg').text('Something went wrong.');
      $('#errormsg').fadeIn();
      $('#successmsg').fadeOut();
    }
});

}
</script>

<?php
} else {
?>
<div class="fre-service">
<div class="container">
<h2 id="title_service">
Get bids to start working and earn.</h2>
<div class="fre-service-content">
<div class="row">
<div class="col-md-1 hidden-sm"></div>
<div class="col-md-10">
<div class="row fre-service-package-list">
<div class="col-md-4 col-sm-6">
<div class="fre-service-pricing">
<div class="service-price">
<h2>Ksh 200</h2>
</div>
<div class="service-info">
<h3>Starter</h3>
<p></p>
<p>
Buy 10 bids to start applying for available jobs
</p>
<p></p>
</div>
<a class="fre-service-btn primary-color-hover" href="/purchase?p=1">Purchase</a>
</div>
</div>
<div class="col-md-4 col-sm-6">
<div class="fre-service-pricing">
<div class="service-price">
<h2>Ksh 350</h2>
</div>
<div class="service-info">
<h3>Freelancer Plus(Most Popular)</h3>
<p></p>
<p>
20 bids for an active freelancer
</p>
<p>
</p>
<p></p>
</div>
<a class="fre-service-btn primary-color-hover" href="/purchase?p=2">Purchase</a>
</div>
</div>
<div class="col-md-4 col-sm-6">
<div class="fre-service-pricing">
<div class="service-price">
<h2>Ksh 500</h2>
</div>
<div class="service-info">
<h3>Freelancer Active (Recommended)</h3>
<p></p>
<p>
35 bids for a super active freelancer
</p>
<p>
</p>
<p>
</p>
<p></p>
</div>
<a class="fre-service-btn primary-color-hover" href="/purchase?p=3">Purchase</a>
</div>
</div>
<div class="col-md-4 col-sm-6">
<div class="fre-service-pricing">
<div class="service-price">
<h2>Ksh 750</h2>
</div>
<div class="service-info">
<h3>Agency(Best Value)</h3>
<p></p>
<p>
50 bids for a super active freelancer
</p>
<p>
</p>
<p>
</p>
<p>
</p>
<p></p>
</div>
<a class="fre-service-btn primary-color-hover" href="/purchase?p=4">Purchase</a>
</div>
</div>
</div>
</div>
<div class="col-md-1 hidden-sm"></div>
</div>
</div>
</div>
</div>
<?php
}
?>

</main>


<?php
include "footer.php";
?>
</body>
</html>