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
if (null == getProfile($conn, $_SESSION['e_id'])) {
  echo '<script>window.location.assign("/complete-profile");</script>';
}

$title = 'Deposit - Add funds to your account. Online Writing Jobs Marketplace. Get Online Writing Jobs or Hire Writers!';
include 'header.php';
?>
<main id="main" class="main">


  <div class="pagetitle">
    <h1>Add funds to your account</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Deposit</li>
      </ol>
    </nav>
  </div>
  <!-- End Page Title -->

  <section class="section dashboard">
    <div class="row">

      <!-- Balance Card -->
      <div class="col-12 col-md-6">
        <div class="card info-card sales-card">

          <div class="filter">
            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
              <li><a class="dropdown-item" href="/withdraw">Withdraw</a></li>
              <?php if (getAccType($conn, $_SESSION['e_id']) == "Freelancer") {
                ?>
                <li><a class="dropdown-item" href="/purchase">Purchase bids</a></li>
                <?php
              } else {
                ?>
                <li><a class="dropdown-item" href="/myprojects?s=2">Approve project payment</a></li>
                <?php
              } ?>
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


    <div class="container col-md-6">
      <form id="deposit-form">
        <div class="alert alert-danger" id="depoError" style="display:none;" role="alert"></div>
        <div class="alert alert-success" id="depoSuccess" style="display:none;" role="alert"></div>
        <!-- Name input -->
        <div class="col-12">
          <label class="form-label">Transaction code here :</label></br>
          <div class="d-flex">
          <div class="col-9">
            <input required type="text" minlength='10' maxlength="10" name="mpesacode" placeholder="Mpesa code" class="form-control" />
          <input type="hidden" name="action" value="deposit" />
          </div>
          <div class="col-4" style="margin-left:3px;">
        <button type="submit" id="depo" class="btn btn-primary btn-block mb-4">Deposit</button>
      </div>
    </div>
    </div>
  </form>
</div>

<!-- depo Card -->
<div class="col-12">
  <div class="card info-card sales-card">

    <div class="filter">
      <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
        <li><a class="dropdown-item" href="/contact">Need Help? Contact us</a></li>
      </ul>
    </div>

    <div class="card-body">
      <h5 class="card-title">Steps to deposit</span></h5>
    <p>
      Go to your M-Pesa menu on your mobile phone.
    </p>
    <p>
      Select "Lipa na M-Pesa".
    </p>
    <p>
      Select "Buy Goods and Services".
    </p>
    <p>
      Enter the Till Number <b>8325552</b> and press "OK".
    </p>
    <p>
      Enter the amount you wish to send and press "OK".
    </p>
    <p>
      Confirm that the details displayed on your screen are correct and press "OK".
    </p>
    <p>
      Enter your M-Pesa PIN and press "OK".
    </p>
    <p>
      You will receive a confirmation message from M-Pesa with a unique transaction code.
    </p>
    <p>
      Copy and paste this transaction code on the input above then press "Deposit"
    </p>
    <br /><b>If you're having trouble don't hesitate to contact us admin@ewriters.co.ke or <a href="/contact">here</a></b>
</div>

</div>
</div>




<?php if (getDeposits($conn, $_SESSION['e_id'])->rowCount() > 0) {
?>
<!-- Purchases -->
<div class="col-12">
<div class="card recent-sales overflow-auto">

<div class="card-body">
<h5 class="card-title">Deposits History</span></h5>

<table class="table table-borderless datatable">
<thead>
<tr>
<th scope="col">id</th>
<th scope="col">Amount</th>
<th scope="col">Date</th>
<th scope="col">Method</th>
</tr>
</thead>
<tbody>
<?php foreach (getDeposits($conn, $_SESSION['e_id'])->fetchAll() as $row) {
?>
<tr>
<th scope="row"><a href="#"><?php echo $row['id']; ?></a></th>
<td><b>Ksh <?php echo number_format($row['amount']); ?></b></td>
<td><?php echo formatDate($row['date']); ?></td>
<td><?php echo $row['method']; ?></td>
</tr>
<?php
} ?>
</tbody>
</table>

</div>

</div>
</div>
<?php
} ?>
<!-- End Recent Sales -->



</div>
</section>
<script>
$(document).ready(function() {
$("#deposit-form").submit(function(e) {
e.preventDefault();
$('#depo').LoadingOverlay("show");

$.ajax({
type: "POST",
url: "/func.php",
data: $("#deposit-form").serialize(),
success: function(response) {
if (response === "success") {
$('#depoSuccess').text('Deposit successful!');
$("#depoSuccess").fadeIn();
$("#deposit-form")[0].reset();
$('#depoError').fadeOut();
location.reload();
} else {
$('#depo').LoadingOverlay("hide");
$("#depoError").text(response);
$("#depoError").fadeIn();
$("#depoSuccess").fadeOut();
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