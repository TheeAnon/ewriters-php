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
if (!isset($_GET['p'])) {
  echo '<script>window.location.assign(document.referrer)</script>';
}

$title = 'Project - Project details. Best Online Writing Jobs Marketplace. Get Online Writing Jobs or Hire Writers!';
include 'header.php';
include 'db.php';
?>
<main id="main" class="main">

  <div class="fre-page-wrapper">
    <?php
    if (null == getProject($conn, $_GET['p'])) {
      echo '<script>window.location.assign("/gigs")</script>';
    }
    foreach (getProject($conn, $_GET['p']) as $row) {
      ?>
      <div class="fre-project-detail-wrap">
        <div class="project-detail-box">
          <div class="project-detail-info">
            <div class="row">
              <div class="col-lg-8 col-md-7">
                <h1 class="project-detail-title"><?php echo $row['title']; ?></h1>
                <ul class="project-bid-info-list">
                  <li><span>Bids</span><span class="secondary-color"><?php echo getProjectBids($conn, $row['id'], 'All', 'None')->rowCount(); ?></span></li>
                  <li>
                    <span>Budget</span>
                    <span class="secondary-color">Ksh <?php echo number_format($row['budget']); ?></span>
                  </li>
                </ul>
              </div>
              <div class="col-lg-4 col-md-5">
                <p class="project-detail-posted">
                  <?php echo formatDate($row['date']); ?>
                </p>
                <span class="project-detail-status secondary-color"><?php echo $row['status']; ?><span></span>
                </span>
                <div class="project-detail-action">
                  <?php if ($row['employer'] == $_SESSION['e_id']) {
                    if ($row['status'] == 'In progress') {
                      ?>
                      <button onclick="complete()" class="btn btn-primary">Mark as Completed</button>
                      <form method="post" id="complete-form" style="display:none;">
                        </br>
                        </br>
                        <div class="alert alert-danger" id="cpError" style="display:none;" role="alert"></div>
                        <div class="alert alert-success" id="cpSuccess" style="display:none;" role="alert"></div>
                        <div class="mb-3  col-6">
                          <label class="form-label">Agreed Budget</label>
                          <input required type="number" class="form-control" name="budget">
                          <input type="hidden" value="<?php echo $_GET['p']; ?>" name="project">
                          <input type="hidden" value="completeproject" name="action">
                        </div>
                        <div class="mb-3 col-6">
                          <label class="form-label">Rate freelancer</label>
                          <input required type="number" maxlength="1" class="form-control" name="employer_rating">
                        </div>
                        <!-- bid input -->
                        <div class="col-12">
                          <label class="form-label">Review about freelancer and project</label>
                          <textarea class="form-control" name="employer_review" rows="3"></textarea>
                        </div>
                      </br>
                        <div id="cpbtn" style="display:inline-block;">
                          <button type="submit" class="btn btn-primary">Complete project</button>
                          <a onclick="cpClose()" class="btn btn-outline-dark">Cancel</a>
                        </div>
                      </br>
                      </br>
                      </form>
                      <?php
                    } ?>
                    <?php
                  } else if (getAccType($conn, $_SESSION['e_id']) == 'Freelancer') {
                    if ($row['status'] == 'Active') {
                      if (getProjectBids($conn, $row['id'], $_SESSION['e_id'], 'None')->rowCount() == 0) {
                        ?>
                        <a onclick="bidPopup();" class="fre-normal-btn primary-bg-color">Bid</a>
                        <form method="post" id="bid-form" style="display:none;">
                        </br>
                        </br>
                        <div class="alert alert-danger" id="bidError" style="display:none;" role="alert"></div>
                        <div class="alert alert-success" id="bidSuccess" style="display:none;" role="alert"></div>
                        <div class="mb-3">
                          <label class="form-label">Your Budget</label>
                          <input required type="number" class="form-control" name="budget">
                          <input type="hidden" value="<?php echo $_GET['p']; ?>" name="project">
                          <input type="hidden" value="bid" name="action">
                        </div>
                        <!-- bid input -->
                        <div class="col-12">
                          <label class="form-label">Bid</label>
                          <textarea required class="form-control" name="bid" rows="4"></textarea>
                        </div>
                      </br>
                        <div id="bidbtn" style="display:inline-block;">
                          <button type="submit" class="btn btn-primary">Send Bid</button>
                          <a onclick="bidPopupClose()" class="btn btn-outline-dark">Cancel</a>
                        </div>
                      </br>
                      </br>
                      </form>
                      <?php
                    }}} ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="project-detail-box no-padding">
        <div class="project-detail-desc">
          <h4>Desciption</h4>
          <p>
            <?php echo $row['description']; ?>
          </p>
        </div>
        <div class="project-detail-extend">
          <div class="project-detail-category">
            <h4>Category</h4>
            <a class="secondary-color"><?php echo $row['category']; ?></a>
          </div>
          <div class="project-detail-about">
            <h4>About Employer</h4>
            <div class="d-flex">
              <div>
                <?php
                foreach (getProfile($conn, $row['employer']) as $pro) {
                  ?>
                  <span><?php echo $pro['rating']; ?><sub>/5</sub><i class="bi bi-star"></i></span></br>
                  <span class=""><?php echo getProjectsES($conn, 'All', $row['employer'])->rowCount(); ?> projects posted</span></br>
                  <span><?php echo getProjectsES($conn, 'Completed', $row['employer'])->rowCount(); ?> freelancers hired</span></br>
                  <span><?php echo getProjectsES($conn, 'Active', $row['employer'])->rowCount(); ?> Active projects</span>
                  <?php
                } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="project-detail-box">
        <div class="freelancer-bidding-head">
          <div class="row">
            <div class="col-md-8 col-sm-12">
              <div class="col-free-bidding">
                BIDS (<?php echo getProjectBids($conn, $row['id'], 'All', 'None')->rowCount(); ?>)
              </div>
            </div>
          </div>
        </div>
        <div class="freelancer-bidding" style="background:white;">
          <div class="row freelancer-bidding-item">
            <?php if (getProjectBids($conn, $row['id'], 'All', 'None')->rowCount() > 0) {
              foreach (getProjectBids($conn, $row['id'], 'All', 'None')->fetchAll() as $bid) {
                ?>
                <div class="container col-12 col-md-6 col-lg-3 col-xxs-8 d-flex">
                  <div class="p-3">
                    <div class="align-items-center" style="text-align:center;">
                      <?php foreach (getUser($conn, $bid['freelancer']) as $user) {
                        foreach (getProfile($conn, $bid['freelancer']) as $profile) {
                          ?>
                          <div class="image">
                            <img src="dp/<?php echo $profile['dp']; ?>" class="rounded-circle " height="70" width="70">
                          </div>
                          <div class="ml-3 w-100 col-10">
                            <span class="mb-0 mt-0"><b><?php echo getNames($conn, $bid['freelancer']); ?></b></span>
                            <div class="p-2 mt-2 bg-primary d-flex justify-content-between rounded text-white stats">
                              <div class="d-flex flex-column">
                                <small class="articles">Projects</small>
                                <small class="number1"><?php echo getProjectsDone($conn, $bid['freelancer'])->rowCount(); ?></small>
                              </div>
                              <div class="d-flex flex-column">
                                <small class="rating">Rating</small>
                                <span class="number3"><i class="bi bi-star"></i> <?php echo getRating($conn, $bid['freelancer']); ?><sub>/5</sub></span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <?php if ($_SESSION['e_id'] == $row['employer']) {
                          if ($row['status'] == 'Active') {
                            ?>
                            <div id="overlay" class="button mt-2" style="display:inline-block;">
                              <a href="/messages?u=<?php echo $bid['freelancer'];?>"><button class="btn btn-sm btn-outline-primary">Chat</button></a>
                              <button onclick="hire(<?php echo $bid['freelancer'];?>)" class="btn btn-sm btn-primary">Hire</button>
                            </div>
                            <?php
                          }} ?>
                        </div>
                        <div class="p-3">
                          <h5>Bid <?php if ($bid['status'] == 'Won') {
                              ?>
                              <sup style="color:green;">Won</sup>
                              <?php
                            } ?></h5>
                          <?php if ($_SESSION['e_id'] == $row['employer']) {
                            ?>
                            <p>
                              <?php echo $bid['bid']; ?>
                            </p>
                            <?php
                          } else if ($_SESSION['e_id'] == $bid['freelancer']) {
                            ?>
                            <p>
                              <?php echo $bid['bid']; ?>
                            </p>
                            <?php
                          } else {
                            ?>
                            <p>
                              Only project owner can view this information.
                            </p>
                            <?php
                          } ?>
                        </div>
                      </div>
                      <?php
                    }}}} else {
                ?>
                <div>
                  <p>
                    No bids yet
                  </p>
                </div>
                <?php
              } ?>
            </div>
          </div>
        </div>
      </div>
      <?php
    } ?>



    <script>
      function bidPopup() {
        $('#bid-form').fadeIn();
      }
      function bidPopupClose() {
        $('#bid-form').fadeOut();
      }
      function complete() {
        $('#complete-form').fadeIn();
      }
      function cpClose() {
        $('#complete-form').fadeOut();
      }
      function hire(id){
          $('#overlay').LoadingOverlay("show");
          var d = {
            action:"hire", 
            freelancer:id,
            project:"<?php echo $_GET['p'];?>",
          }
          $.ajax({
            type: "POST",
            url: "/func.php",
            data: d,
            success: function(response) {
              if (response === "success") {
                location.reload();
              } else {
                alert('Something went wrong.');
                $('#overlay').LoadingOverlay("show");
              }
            }
          });
        }
    </script>
    <script>
      $(document).ready(function() {
        $("#bid-form").submit(function(e) {
          e.preventDefault();
          $('#bidbtn').LoadingOverlay("show");

          $.ajax({
            type: "POST",
            url: "/func.php",
            data: $("#bid-form").serialize(),
            success: function(response) {
              if (response === "success") {
                $('#bidSuccess').text('Bid sent successfully! Good luck.');
                $("#bidSuccess").fadeIn();
                $("#bid-form")[0].reset();
                $('#bidError').fadeOut();
                location.reload();
              } else {
                $("bidError").text(response);
                $("bidError").fadeIn();
                $("bidSuccess").fadeOut();
                $('#cpbtn').LoadingOverlay("hide");
              }
            }
          });
        });
        
        
        $("#complete-form").submit(function(e) {
          e.preventDefault();
          $('#cpbtn').LoadingOverlay("show");

          $.ajax({
            type: "POST",
            url: "/func.php",
            data: $("#complete-form").serialize(),
            success: function(response) {
              if (response === "success") {
                $('#cpSuccess').text('Project completed successfully! Payment has been released.');
                $("#cpSuccess").fadeIn();
                $("#complete-form")[0].reset();
                $('#cpError').fadeOut();
                location.reload();
              } else {
                $("cpError").text(response);
                $("cpError").fadeIn();
                $("cpSuccess").fadeOut();
                $('#cpbtn').LoadingOverlay("hide");
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