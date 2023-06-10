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
if(null == getProfile($conn, $_SESSION['e_id'])){
  echo '<script>window.location.assign("/complete-profile");</script>';
}

$title = 'Dashboard - Best Online Writing Jobs Marketplace. Get Online Writing Jobs or Hire Writers!';
include 'header.php';
?>
<main id="main" class="main">


  <div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>
    </nav>
  </div>
  <!-- End Page Title -->

  <section class="section dashboard">
    <div class="row">

      <!-- Left side columns -->
      <div class="col-lg-8">
        <div class="row">

          <!-- Sales Card -->
          <div class="col-6 col-lg-4">
            <div class="card info-card sales-card">

              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <li><a class="dropdown-item" href="/withdraw">Withdraw</a></li>
                  <li><a class="dropdown-item" href="/deposit">Deposit</a></li>
                  <?php if (getAccType($conn, $_SESSION['e_id']) == "Freelancer") {
                    ?>
                    <li><a class="dropdown-item" href="/purchase">Purchase bids</a></li>
                    <?php }else { ?>
                    <li><a class="dropdown-item" href="/myprojects?s=2">Approve project payment</a></li>
                    <?php } ?>
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
          <!-- End Sales Card -->

          <!-- Revenue Card -->
          <div class="col-6 col-lg-4">
            <div class="card info-card revenue-card">

              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <?php if(getAccType($conn, $_SESSION['e_id']) == "Freelancer") {?> 
                  <li><a class="dropdown-item" href="/gigs">Find gigs</a></li>
                  <li><a class="dropdown-item" href="/gigs">Submit Gig</a></li>
                  <?php }else { ?>
                  <li><a class="dropdown-item" href="/post">Post Project</a></li>
                  <li><a class="dropdown-item" href="/myprojects">Approve project payment</a></li>
                  <?php } ?>

                </ul>
              </div>

              <div class="card-body">
                <h5 class="card-title">Projects</h5>
                <div class="ps-3" style="padding:0;">
                  <small><?php echo getCompleteProjects($conn, $_SESSION['e_id'], 'In progress')->rowCount(); ?> <sub>Ongoing projects</sub></small></br>
                  <small><?php echo getCompleteProjects($conn, $_SESSION['e_id'], 'Completed')->rowCount(); ?> <sub>Completed projects</sub></small></br>
                  <small><?php echo getCompleteProjects($conn, $_SESSION['e_id'], 'In review')->rowCount(); ?> <sub>Projects under review</sub></small>
                </div>
              </div>

            </div>
          </div>
          <!-- End Revenue Card -->


          <?php if (getAccType($conn, $_SESSION['e_id']) == "Freelancer") { ?>
          <?php if(getCompleteProjects($conn, $_SESSION['e_id'], 'All')->rowCount() > 0){ ?>
          <!-- projects -->
          <div class="col-12">
            <div class="card recent-sales overflow-auto">

              <div class="card-body">
                <h5 class="card-title">Projects</span></h5>

              <table class="table table-borderless datatable">
                <thead>
                  <tr>
                    <th scope="col">Project</th>
                    <th scope="col">Employer</th>
                    <th scope="col">Employer rating</th>
                    <th scope="col">My rating</th>
                    <th scope="col">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach (getCompleteProjects($conn, $_SESSION['e_id'], 'All')->fetchAll() as $row){?>
                  <tr>
                    <th scope="row"><a href="/project?p=<?php echo $row['project']; ?>"><?php echo $row['project']; ?></a></th>
                    <td><?php echo getNames($conn, getEmployerID($conn, $row['project'])); ?></td>
                    <td><a href="#" class="text-primary"><? echo $row['employer_rating']; ?></a></td>
                    <td><?php echo $row['freelancer_rating']; ?></td>
                    <td><?php if($row['status']=='Paid'){ ?><span class="badge bg-success"><?php echo $row['status']; ?></span><?php }else { echo '<span class="badge bg-warning">'.$row["status"].'</span>'; } ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>

            </div>

          </div>
        </div>
        <?php }} ?>
        <!-- End Recent Sales -->

        <?php if(getWithdrawals($conn, $_SESSION['e_id'], 'All')->rowCount() > 0) { ?>
        <!-- Withdrawals -->
        <div class="col-12 col-md-6">
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
                <?php foreach(getWithdrawals($conn, $_SESSION['e_id'], 'All')->fetchAll() as $row) { ?>
                <tr>
                  <th scope="row"><a href="#"><?php echo $row['id']; ?></a></th>
                  <td><b>Ksh <?php echo number_format($row['amount']); ?></b></td>
                  <td><?php echo $row['method']; ?></td>
                  <td><?php echo formatDate($row['date']); ?></td>
                  <td><?php if($row['status']=='Accepted'){ echo '<span class="badge bg-success">'.$row['status'].'</span>';}else if($row['status']=='Pending'){ echo '<span class="badge bg-warning">'.$row['status'].'</span>';}else{echo '<span class="badge bg-danger">'.$row['status'].'</span>';}?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>

          </div>

        </div>
      </div>
      <?php } ?>
      <!-- End Recent Sales -->

      <?php if(getDeposits($conn, $_SESSION['e_id'])->rowCount() > 0) { ?>
      <!-- Purchases -->
      <div class="col-12 col-md-6">
        <div class="card recent-sales overflow-auto">

          <div class="card-body">
            <h5 class="card-title">Deposits</span></h5>

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
              <?php foreach(getDeposits($conn, $_SESSION['e_id'])->fetchAll() as $row) { ?>
              <tr>
                <th scope="row"><a href=""><?php echo $row['id']; ?></a></th>
                <td><b>Ksh <?php echo number_format($row['amount']); ?></b></td>
                <td><?php echo formatDate($row['date']); ?></td>
                <td><?php echo $row['method']; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>

        </div>

      </div>
    </div>
    <?php } ?>
    <!-- End Recent Sales -->

    <?php if(getPurchases($conn, $_SESSION['e_id'])->rowCount() > 0) { ?>
    <!-- Purchases -->
    <div class="col-12">
      <div class="card recent-sales overflow-auto">

        <div class="card-body">
          <h5 class="card-title">Purchases</span></h5>

        <table class="table table-borderless datatable">
          <thead>
            <tr>
              <th scope="col">id</th>
              <th scope="col">Package</th>
              <th scope="col">Amount</th>
              <th scope="col">Date</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach(getPurchases($conn, $_SESSION['e_id'])->fetchAll() as $row) { ?>
            <tr>
              <th scope="row"><a href="#"><?php echo $row['id']; ?></a></th>
              <td><?php echo $row['package']; ?></td>
              <td><a href="#" class="text-primary">Ksh <?php echo $row['amount']; ?></a></td>
              <td><?php echo formatDate($row['date']); ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>

      </div>

    </div>
  </div>
  <?php } ?>
  <!-- End Recent Sales -->

  <!-- Gigs -->
  <div class="col-12">
    <div class="card recent-sales overflow-auto">

      <div class="card-body">
        <h5 class="card-title">Gigs</span></h5>
      <ul class="fre-project-list project-list-container">
        <?php foreach(getProjects($conn, '15', 'None')->fetchAll(PDO::FETCH_ASSOC) as $row) { ?>
        <li class="project-item">
          <div class="project-list-wrap">
            <h2 class="project-list-title" style="font-size:15px; font-weight:900; float:left;">
              <a class="secondary-color" title="<?php echo $row['title']; ?>" href="/project?p=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a>
            </h2></br>
            <div class="project-list-info">
              <span><?php echo formatDate($row['date']); ?></span>
              <span><?php echo getProjectBids($conn, $row['id'], 'All', 'None')->rowCount(); ?></span>
              <span>Ksh <?php echo number_format($row['budget']); ?></span>
            </div>
            <div class="project-list-desc">
              <p><?php echo $row['description']; ?></p>
            </div>
            <div class="project-list-skill" style="height: auto; width:100%;">
              <?php echo $row['category']; ?>
              <a href="/project?p=<?php echo $row['id']; ?>"><button class="btn btn-primary" style="float:right;"> Bid >> </button></a>
            </div>
          </div>
        </li>
       <?php } ?>
      </ul>
    </div>
    <a href="/gigs"><button class="btn btn-primary" style="width:100%; border-radius:0;">More Gigs</button></a>
  </div>
</div>
<!-- End Recent Sales -->

</section>

</main>
<?php
include 'footer.php';
?>