<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo $title ?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-PLLPR0TVRV"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-PLLPR0TVRV');
  </script>
  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4287632725987184"
    crossorigin="anonymous"></script>

  <!-- Favicons -->
  <link href="imgs/favicon.png" rel="icon">
  <link href="imgs/favicon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link href="css/index.css" rel="stylesheet">
  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <link rel="stylesheet" id="main-style-css" href="css/styles.css" type="text/css" media="all">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
      <a href="/" class="logo d-flex align-items-center">
        <img src="imgs/favicon.png" alt="eWriters Kenya">
        <span class="d-none d-lg-block">eWriters</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>
    <!-- End Logo -->
    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">
        <?php if (isset($_SESSION['e_id'])) {
          include 'db.php';
          include_once 'func.php';
          ?>
          <li class="nav-item dropdown">
            <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
              <i class="bi bi-bell" style="width:30px; heigh:30px;"></i>
              <?php
              $notifs = getNewNotifications($conn, $_SESSION['e_id'])->rowCount();
              if ($notifs > 0) {
                echo "<span class='badge bg-primary badge-number'>".$notifs."</span>";
              } ?>
            </a><!-- End Notification Icon -->

            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
              <?php
              if ($notifs > 0) {
                ?>
                <li class="dropdown-header">
                  You have <?php echo $notifs; ?> new notifications
                  <a href="/notifications"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                </li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <?php
              }
              $notifications = getNotifications($conn, $_SESSION['e_id']);
              if ($notifications->rowCount() > 0) {
                foreach ($notifications->fetchAll(PDO::FETCH_ASSOC) as $row) {
                  ?>
                  <li class="notification-item d-flex">
                    <div>
                      <p>
                        <?php echo $row['notification']; ?>
                      </p>
                      <p>
                        <?php echo formatTime($row['date']); ?>
                      </p>
                    </div>
                    <?php if ($row['status'] == 'Sent') {
                      echo '<b style="font-weight:900; color:green;">&middot;</b>';
                    } ?>
                  </li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <?php
                } ?>

                <li class="dropdown-footer">
                  <a href="#">Show all notifications</a>
                </li>
                <?php
              } else {
                ?>
                <li class="notification-item">
                  <div>
                    <p>
                      No notification
                    </p>
                  </div>
                </li>
                <?php
              } ?>

            </ul>
            <!-- End Notification Dropdown Items -->

          </li><!-- End Notification Nav -->

          <li class="nav-item dropdown">

            <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
              <i class="bi bi-chat-left-text" style="width:30px; heigh:30px;"></i>
              <?php
              $mcount = getNewMessages($conn, $_SESSION['e_id'])->rowCount();
              if ($mcount > 0) {
                echo "<span class='badge bg-primary badge-number'>".$mcount."</span>";
              } ?>
            </a><!-- End Messages Icon -->

            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
              <?php
              if ($mcount > 0) {
                ?>
                <li class="dropdown-header">
                  You have <?php echo $mcount; ?> new messages
                  <a href="/messages"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                </li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <?php
              }
              $messages = getMessages($conn, $_SESSION['e_id']);
              if ($messages->rowCount() > 0) {
                foreach ($messages->fetchAll(PDO::FETCH_ASSOC) as $row) {
                  ?>
                  <li class="message-item d-flex">
                    <a href="#">
                      <img src="dp/<?php echo getDp($conn, $row['sender']); ?>" width="100" alt="Profile" class="object-cover rounded-circle">
                      <div>
                        <h4><?php echo getNames($conn, $row['sender']); ?></h4>
                        <p>
                          <?php echo $row['message']; ?>
                        </p>
                        <p>
                          <?php echo formatTime($row['date']); ?>
                        </p>
                      </div>
                      <?php if ($row['status'] == 'Sent') {
                        echo '<b style="font-weight:900;">&middot;</b>';
                      } ?>
                    </a>
                  </li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <?php
                } ?>

                <li class="dropdown-footer">
                  <a href="/messages">Show all messages</a>
                </li>
                <?php
              } else {
                ?>
                <li class="message-item">
                  <div>
                    <p>
                      No message
                    </p>
                  </div>
                </li>
                <?php
              } ?>

            </ul>
            <!-- End Messages Dropdown Items -->

          </li><!-- End Messages Nav -->

          <li class="nav-item dropdown pe-3">
            <?php foreach (getProfile($conn, $_SESSION['e_id']) as $myprofile) {
              foreach (getUser($conn, $_SESSION['e_id']) as $user) {
                ?>
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                  <img src="dp/<?php echo $myprofile['dp']; ?>" alt="Profile" width="35" height="35" class="object-cover rounded-circle">
                  <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo getNames($conn, $_SESSION['e_id']); ?></span>
                </a><!-- End Profile Iamge Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                  <li class="dropdown-header">
                    <h6><?php echo getNames($conn, $_SESSION['e_id']); ?></h6>
                    <span><?php echo $user['type']; ?></span>
                  </li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>

                  <li>
                    <a class="dropdown-item d-flex align-items-center" href="/myprofile">
                      <i class="bi bi-person"></i>
                      <span>My Profile</span>
                    </a>
                  </li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>

                  <li style="display:none;">
                    <a class="dropdown-item d-flex align-items-center" href="/settings">
                      <i class="bi bi-gear"></i>
                      <span>Account Settings</span>
                    </a>
                  </li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>

                  <li>
                    <a class="dropdown-item d-flex align-items-center" href="/logout">
                      <i class="bi bi-box-arrow-right"></i>
                      <span>Sign Out</span>
                    </a>
                  </li>
                  <?php
                }} ?>
            </ul>
            <!-- End Profile Dropdown Items -->
          </li><!-- End Profile Nav -->
          <?php
        } else {
          ?>
          <div style="display:inline-block; margin-right:2px;">
            <a href="/login"><button class="btn btn-primary">Sign in</button></a>
            <a href="/register"><button class="btn btn-primary">Sign up</button></a>
          </div>
          <?php
        } ?>
      </ul>
    </nav>

    <!-- End Icons Navigation -->

  </header>
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
      <?php if (isset($_SESSION['e_id'])) {
        ?>
        <li class="nav-item">
          <a class="nav-link " href="/dashboard">
            <i class="bi bi-grid"></i>
            <span>Dashboard</span>
          </a>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
          <?php if (getAccType($conn, $_SESSION['e_id']) == "Freelancer") {
            ?>
            <a class="nav-link collapsed" href="/gigs">
              <i class="bi bi-menu-button-wide"></i><span>Find gigs</span>
            </a>
            <a class="nav-link collapsed" href="/training">
              <i class="bi bi-book"></i><span>Training program</span>
            </a>
            <?php
          } else {
            ?>
            <a class="nav-link collapsed" data-bs-target="#projects-nav" data-bs-toggle="collapse" href="/gigs">
              <i class="bi bi-menu-button-wide"></i><span>Projects</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="projects-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
              <li>
                <a href="/post">
                  <i class="bi bi-circle"></i><span>Post a project</span>
                </a>
              </li>
              <li>
                <a href="/myprojects">
                  <i class="bi bi-circle"></i><span>My projects</span>
                </a>
              </li>
              <li>
                <a href="/drafts">
                  <i class="bi bi-circle"></i><span>Drafts</span>
                </a>
              </li>
            </ul>
            <?php
          } ?>
        </li><!-- End Components Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-layout-text-window-reverse"></i><span>Account</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="/deposit">
                <i class="bi bi-circle"></i><span>Deposit</span>
              </a>
            </li>
            <li>
              <a href="/withdraw">
                <i class="bi bi-circle"></i><span>Withdraw</span>
              </a>
            </li>
            <?php if (getAccType($conn, $_SESSION['e_id']) == 'Freelancer') {
              ?>
              <li>
                <a href="/purchase">
                  <i class="bi bi-circle"></i><span>Purchase bids</span>
                </a>
              </li>
              <li>
                <a href="/myprojects">
                  <i class="bi bi-circle"></i><span>My projects</span>
                </a>
              </li>
              <?php
            } ?>
          </ul>
        </li><!-- End Tables Nav -->
        <?php
      } ?>
      <li class="nav-heading">Pages</li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="/">
          <i class="bi bi-house"></i>
          <span>Home</span>
        </a>
      </li>
      <?php if(!isset($_SESSION['e_id'])){?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="/training">
          <i class="bi bi-book"></i>
          <span>Training program</span>
        </a>
      </li><?php } ?>

      <li class="nav-item">
        <a class="nav-link collapsed" href="/faqs">
          <i class="bi bi-question-circle"></i>
          <span>F.A.Q</span>
        </a>
      </li><!-- End F.A.Q Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="/contact">
          <i class="bi bi-envelope"></i>
          <span>Contact us</span>
        </a>
      </li><!-- End Contact Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="/about">
          <i class="bi bi-book"></i>
          <span>About us</span>
        </a>
      </li><!-- End Contact Page Nav -->
    </ul>

  </aside>
  <!-- End Sidebar-->