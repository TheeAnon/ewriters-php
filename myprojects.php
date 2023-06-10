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

$title = 'My Projects - Find projects on the Best Online Writing Jobs Marketplace. Get Online Writing Jobs or Hire Writers!';
include 'header.php';
include 'db.php';
?>
<main id="main" class="main">


  <div class="pagetitle">
    <h1>Your projects</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
        <li class="breadcrumb-item active">Your projects</li>
      </ol>
    </nav>
  </div>


  <div class="fre-project-list-box">
    <div class="fre-project-list-wrap">
      <div class="fre-project-result-sort">
        <div class="row">
          <h2 id="title_project" style="font-size:12px; font-weight:600;">Awarded projects, Completed projects and projects in progress.</h2>
          <div class="col-sm-8 col-sm-pull-4">
            <div class="fre-project-result">
              <p>
                <span class="plural "><span class="found_post"><?php
                if(getAccType($conn, $_SESSION['e_id']) == 'Freelancer'){
                echo getProjectsFreelancer($conn, $_SESSION['e_id'])->rowCount();}else {
                echo getProjectsES($conn, 'All', $_SESSION['e_id'])->rowCount();} ?></span> projects found</span>
              </p>
            </div>
          </div>
        </div>
      </div>  
      <?php if(getAccType($conn, $_SESSION['e_id']) == 'Freelancer'){
      if (getProjectsFreelancer($conn, $_SESSION['e_id'])->rowCount() > 0) {
      ?>
      <ul class="fre-project-list project-list-container">
        <?php foreach (getProjectsFreelancer($conn, $_SESSION['e_id'])->fetchAll(PDO::FETCH_ASSOC) as $row) {?>
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
              </div>
            </div>
          </li>
          <?php
        } ?>
      </ul>
      <?php }else { ?> 
      <div class="profile-no-result">
        <div class="profile-content-none">
          <h2>No projects. </h2>
          <ul>
            <li>You do not have any projects : pending, in progress or completed.</li>
          </ul>
        </div>
      </div>
      <?php }} else {?>
      
      <?php if (getProjectsES($conn, 'All', $_SESSION['e_id'])->rowCount() > 0) {
      ?>
      <ul class="fre-project-list project-list-container">
        <?php foreach (getProjectsES($conn, 'All', $_SESSION['e_id'])->fetchAll(PDO::FETCH_ASSOC) as $row) {?>
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
              </div>
            </div>
          </li>
          <?php
        } ?>
      </ul>
      <?php }else { ?> 
      <div class="profile-no-result">
        <div class="profile-content-none">
          <h2>No projects. </h2>
          <ul>
            <li>You do not have any projects : pending, in progress or completed.</li>
          </ul>
        </div>
      </div>
      <?php }} ?>

    </div>
  </div>


</main>
<?php
include 'footer.php';
?>