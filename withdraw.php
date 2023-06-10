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

$title = 'Withdraw - Withdraw your money straight into your mpesa account. Best Online Writing Jobs Marketplace. Get Online Writing Jobs or Hire Writers!';
include 'header.php';
?>
<main id="main" class="main">


  <div class="pagetitle">
    <h1>Withdraw</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Withdraw</li>
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
              <?php if (getAccType($conn, $_SESSION['e_id']) == "Freelancer") {
                ?>
                <li><a class="dropdown-item" href="/withdraw">Find gigs</a></li> 
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
    <form id="withdraw-form">
      <div class="alert alert-danger" id="depoError" style="display:none;" role="alert"></div>
      <div class="alert alert-success" id="depoSuccess" style="display:none;" role="alert"></div>
      <!-- Name input -->
      <div class="col-8 col-md-6">
        <label class="form-label">Amount</label>
        <input required type="number" maxlength="6" name="amount" class="form-control" />
        <input type="hidden" name="action" value="withdraw" />
      </div></br>
  <!-- Submit button -->
  <button type="submit" id="depo" class="btn btn-primary btn-block mb-4">Withdraw</button>
</form>
</div>
    <!-- End Sales Card -->
    <?php if (getWithdrawals($conn, $_SESSION['e_id'], 'All')->rowCount() > 0) {
      ?>
      <!-- Withdrawals -->
      <div class="col-12">
        <div class="card recent-sales overflow-auto">

          <div class="card-body">
            <h5 class="card-title">Withdrawals</span></h5>

          <table class="table table-borderless datatable">
            <thead>
              <tr>
                <th scope="col">id</th>
                <th scope="col">Amount</th>
                <th scope="col">Method</th>
                <th scope="col">Date</th>
                <th scope="col">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach (getWithdrawals($conn, $_SESSION['e_id'], 'All')->fetchAll() as $row) {
                ?>
                <tr>
                  <th scope="row"><a href="#"><?php echo $row['id']; ?></a></th>
                  <td><b>Ksh <?php echo number_format($row['amount']); ?></b></td>
                  <td><?php echo $row['method']; ?></td>
                  <td><?php echo formatDate($row['date']); ?></td>
                  <td><?php if ($row['status'] == 'Accepted') {
                    echo '<span class="badge bg-success">'.$row['status'].'</span>';
                  } else if ($row['status'] == 'Pending') {
                    echo '<span class="badge bg-warning">'.$row['status'].'</span>';
                  } else {
                    echo '<span class="badge bg-danger">'.$row['status'].'</span>';
                  } ?></td>
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
$("#withdraw-form").submit(function(e) {
e.preventDefault();
$('#depo').LoadingOverlay("show");

$.ajax({
type: "POST",
url: "/func.php",
data: $("#withdraw-form").serialize(),
success: function(response) {
if (response === "success") {
$('#depoSuccess').text('Withdraw request successful!');
$("#depoSuccess").fadeIn();
$("#withdraw-form")[0].reset();
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