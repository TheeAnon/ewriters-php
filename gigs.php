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

$title = 'Gigs - Find projects on the Best Online Writing Jobs Marketplace. Get Online Writing Jobs or Hire Writers!';
include 'header.php';
include 'db.php';
?>
<main id="main" class="main">


  <div class="pagetitle">
    <h1>Gigs</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
        <li class="breadcrumb-item active">Gigs</li>
      </ol>
    </nav>
  </div>


  <div class="fre-project-list-box">
    <div class="fre-project-list-wrap">
      <div class="fre-project-result-sort">
        <div class="row">
          <h2 id="title_project" style="font-size:12px; font-weight:600;">Numerous gigs available online.
          </br><i style="font-size:10px;">Bid, submit, earn.</i></h2></br></br>
          <div class="col-sm-4 col-sm-push-8">
            <div class="fre-project-sort">
              <select class="fre-chosen-single sort-order" id="project_orderby"
                name="orderby">
                <option value="date">Latest Projects</option>
                <option value="et_budget">Highest Budget</option>
                <option value="et_budget">Highest Bids</option>
                <option value="et_budget">Lowest Bids</option>
              </select>
            </div>
          </div>
          <div class="col-sm-8 col-sm-pull-4">
            <div class="fre-project-result">
              <p>
                <span class="plural "><span class="found_post"><?php echo getProjectsStatus($conn, 'All')->rowCount(); ?></span> projects found</span>
              </p>
            </div>
          </div>
        </div>
      </div>
      <ul class="fre-project-list project-list-container">
        <?php foreach (getProjects($conn, 'All', 'None')->fetchAll(PDO::FETCH_ASSOC) as $row) {
          ?>
          <li class="project-item">
            <div class="project-list-wrap">
              <h2 class="project-list-title" style="font-size:15px; font-weight:900; float:left;">
                <a class="secondary-color" title="<?php echo $row['title']; ?>" href="/project?p=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a>
              </h2></br>
              <div class="project-list-info">
                <span style="color:grey;"><?php echo formatDate($row['date']); ?></span>
                <span style="color:grey;"><?php echo getProjectBids($conn, $row['id'], 'All', 'None')->rowCount(); ?></span>
                <span>Ksh <?php echo number_format($row['budget']); ?></span>
              </div>
              <div class="project-list-desc">
                <p><?php echo $row['description']; ?></p>
              </div>
              <div class="project-list-skill" style="height: auto; width:100%; color:grey;">
                <?php echo $row['category']; ?>
                <a href="/project?p=<?php echo $row['id']; ?>"><button class="btn btn-primary" style="float:right;"> Bid >> </button></a>
              </div>
            </div>
          </li>
          <?php
        } ?>
      </ul>
      <div style="display:none;" class="profile-no-result">
        <div class="profile-content-none">
          <p>
            There are no results that match your search!
          </p>
          <ul>
            <li>Try more general terms</li>
            <li>Try another search method</li>
            <li>Try to search by keyword</li>
          </ul>
        </div>
      </div>

    </div>
  </div>


</main>
<?php
include 'footer.php';
?>