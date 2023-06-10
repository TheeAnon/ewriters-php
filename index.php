<?php
session_start();
include 'func.php';
if (isset($_SESSION['e_id'])) {
  echo '<script>window.location.assign("/dashboard")</script>';
  exit;
} else if (isset($_COOKIE['rememberme'])) {
  $_SESSION['e_id'] = decryptCookie($_COOKIE['rememberme']);
  echo '<script>window.location.assign("/dashboard")</script>';
  exit;
}

$title = 'Welcome to the best Online Writing Jobs Marketplace. Get Online Writing Jobs or Hire Writers!';
include 'header.php';
include 'db.php';
?>
<header class="bg-dark py-5">
  <div class="container px-5">
    <div class="row gx-5 align-items-center justify-content-center">
      <div class="col-lg-8 col-xl-7 col-xxl-6">
        <div class="my-5 text-center text-xl-start">
          <h1 class="display-5 fw-bolder text-white mb-2">Best Freelancing Marketplace.</h1>
          <p class="lead fw-normal text-white-50 mb-4">
            Join Kenya's top-rated freelancing platform and earn money by completing simple tasks. Access a pool of highly skilled writers to complete your projects efficiently. Don't wait any longer, sign up today.
          </p>
          <div class="d-grid gap-3 d-sm-flex justify-content-sm-center justify-content-xl-start">
            <a class="btn btn-primary btn-lg" href="/register">Get Started</a>
            <a class="btn btn-outline-light btn-lg" href="/about">Learn More</a>
          </div>
        </div>
      </div>
      <div class="col-xl-5 col-xxl-6 d-none d-xl-block text-center">
        <img class="img-fluid rounded-3 my-5" src="https://dummyimage.com/600x400/343a40/6c757d" alt="..." />
    </div>
  </div>
</div>
</header>

<!-- Features section-->
<section class="py-5" id="features">
<div class="container px-5 my-5">
<div class="row gx-5">
<div class="col-lg-4 mb-5 mb-lg-0">
<h2 class="fw-bolder mb-0">Our platform offers an excellent opportunity for your freelancing career.</h2>
</div>
<div class="col-lg-8">
<div class="row gx-5 row-cols-1 row-cols-md-2">
<div class="col mb-5 h-100 shadow-lg p-3 mb-5 bg-body-tertiary rounded">
<i class="bi bi-person"></i>
<h2 class="h5">Create a free account</h2>
<p class="mb-0">
Sign up for an account on our platform. It's quick, easy, and free.
</p>
</div>
<div class="col mb-5 h-100 shadow-lg p-3 mb-5 bg-body-tertiary rounded">
<i class="bi bi-toggles2"></i>
<h2 class="h5">Bid on projects</h2>
<p class="mb-0">
Browse through the available projects and choose the ones that match your skills and expertise. Bid on these projects and wait for the project owners to approve your proposal.
</p>
</div>
<div class="col mb-5 h-100 shadow-lg p-3 mb-5 bg-body-tertiary rounded">
<i class="bi bi-book"></i>
<h2 class="h5">Deliver quality work</h2>
<p class="mb-0">
Once you have been awarded a project, make sure you complete it before the deadline. Submit the completed project to the project owner for review.
</p>
</div>
<div class="col mb-5 h-100 shadow-lg p-3 mb-5 bg-body-tertiary rounded">
<i class="bi bi-collection"></i>
<h2 class="h5">Work review and payment</h2>
<p class="mb-0">
If the project owner is satisfied with your work, they will approve it and release payment to you.
</p>
</div>
<div class="col mb-5 h-100 shadow-lg p-3 mb-5 bg-body-tertiary rounded">
<i class="bi bi-cash"></i>
<h2 class="h5">Withdraw your earnings</h2>
<p class="mb-0">
Congratulations! You have successfully completed a project and earned money from it. You can now withdraw your earnings to your preferred payment method.
</p>
</div>
<div class="col mb-5 mb-md-0 h-100">
<i class="bi bi-tick"></i>
<h2 class="h5">We've got you</h2>
<p class="mb-0">
At our platform, we offer a wide range of projects that you can work on, including writing, graphic design, web development, and more. Join us today and start your journey towards financial independence.
</p>
</div>
</div>
</div>
</div>
</div>
</section>
</header>

<!-- Features section-->
<section class="py-5" id="features">
<div class="container px-5 my-5">
<div class="row gx-5">
<div class="col-lg-4 mb-5 mb-lg-0">
<h2 class="fw-bolder mb-0">Are you looking for someone to complete your projects on time and with perfection?</h2>
</div>
<div class="col-lg-8">
<div class="row gx-5 row-cols-1 row-cols-md-2">
<div class="col mb-5 h-100 shadow-lg p-3 mb-5 bg-body-tertiary rounded">
<i class="bi bi-person"></i>
<h2 class="h5">Create a free account</h2>
<p class="mb-0">
Sign up for an account on our platform. It's quick, easy, and free.
</p>
</div>
<div class="col mb-5 h-100 shadow-lg p-3 mb-5 bg-body-tertiary rounded">
<i class="bi bi-toggles2"></i>
<h2 class="h5">Post your project</h2>
<p class="mb-0">
Tell us what you need done and your budget.
</p>
</div>
<div class="col mb-5 h-100 shadow-lg p-3 mb-5 bg-body-tertiary rounded">
<i class="bi bi-person"></i>
<h2 class="h5">Pick a professional</h2>
<p class="mb-0">
Choose the right candidate for your project based on their experience, previous projects and reviews. Negotiate terms with them. And kick-start your project.
</p>
</div>
<div class="col mb-5 h-100 shadow-lg p-3 mb-5 bg-body-tertiary rounded">
<i class="bi bi-book"></i>
<h2 class="h5">Review their work</h2>
<p class="mb-0">
Check the quality of the work. We keep your money safe until you are satisfied with the project submitted to you.
</p>
</div>
<div class="col mb-5 h-100 shadow-lg p-3 mb-5 bg-body-tertiary rounded">
<i class="bi bi-cash"></i>
<h2 class="h5">Complete payment</h2>
<p class="mb-0">
Release funds to the candidate.
</p>
</div>
<div class="col mb-5 mb-md-0 h-100">
<i class="bi"></i>
<h2 class="h5">We value you</h2>
<p class="mb-0">
At our platform, we value you. Join us today lets work together.
</p>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- Testimonial section-->
<div class="py-5 bg-light">
<div class="container px-5 my-5">
<div class="row gx-5 justify-content-center">
<div class="col-lg-10 col-xl-7">
<div class="text-center">
<div>
<i>
"I must say that Choosing eWriters has been the best decision for my business. Their quality service has exceeded my expectations. Highly recommend!"</i>
</div>
<div class="d-flex align-items-center justify-content-center">
<img class="rounded-circle me-3 object-cover" src="imgs/default.png" height="30" width="30" />
<div class="fw-bold">
James Karanja
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<!-- Blog preview section-->
<section class="py-5">
<div class="container px-5 my-5 col-12">
<div class="row gx-5 justify-content-center">
<div class="col-lg-8 col-xl-6">
<div class="text-center">
<h2 class="fw-bolder">Latest projects</h2>
</div>
</div>
</div>
<?php foreach (getProjects($conn, '6', 'None')->fetchAll(PDO::FETCH_ASSOC) as $row) {
?>
<div class="container col-md-6">
<div class="mb-5">
<div class="card h-100 shadow border-0">
<div class="card-body p-4">
<div class="badge bg-primary bg-gradient rounded-pill mb-2">
<?php echo $row['category']; ?>
</div>
<b style="float:right;">Ksh. <?php echo $row['budget']; ?></b>
<a class="text-decoration-none link-dark stretched-link" href="/project?p=<?php echo $row['id']; ?>"><h5 class="card-title mb-3" style="font-size:0.9em;"><?php echo $row['title']; ?></h5></a>
<p class="card-text mb-0" style="font-size:0.8em;">
<?php echo $row['description']; ?>
</p>
</div>
<div class="card-footer p-4 pt-0 bg-transparent border-top-0">
<div class="d-flex align-items-end justify-content-between">
<div class="d-flex align-items-center">
<img class="roundedalign-items- object-cover" src="dp/<?php echo getDp($conn, $row['employer']); ?>" style="height:30px; width:30px;" />
<div class="small">
<div class="fw-bold">
eWriters
</div>
<div class="text-muted">
<?php echo formatDate($row['date']); ?>
</div>
</div>
</div>
</div>
</div>
<a href="/project?id=<?php echo $row['id']; ?>"><button class="btn btn-primary btn-outline-dark" style="width:100%;">View project</button></a>
</div>
</div>
</div>
<?php
} ?>
</div>
</section>


<div class="text-center">
<h2>Professional freelancers</h2>
</div>
<section class="col-9" style="margin:auto;">
<div class="row gx-5">
<?php foreach (getUsers($conn, 'Freelancer', 'rating', '6') as $row) {
?>
<div class="container col-6 col-md-4 col-lg-3 col-xxs-8">
<div class="card p-3">
<div class="align-items-center" style="text-align:center;">
<div class="image">
<img src="dp/<?php echo getDp($conn, $row['id']); ?> " class="rounded-circle object-cover" width="100" height="100">
</div>
<div class="ml-3 w-100 col-7">
<span class="mb-0 mt-0"><b><?php echo getNames($conn, $row['id']); ?></b></span>
</div>
<small class="number3"><i class="bi bi-star-fill bg-yellow"></i><?php echo getRating($conn, $row['id']); ?><sub>/5</sub></small>
</div>
<div class="button mt-2" style="display:inline-block;">
<a href="/register"><button class="btn btn-sm btn-outline-primary">Chat</button></a>
<a href="/register"><button class="btn btn-sm btn-primary">Hire</button></a>
</div>
</div>
</div>
<?php
} ?>

</div>
</section>


<section>
<div class="text-center">
<h2>Faqs</h2>
</div>
<div class="container">
<div class="accordion" id="accordionPanelsStayOpenExample">
<div class="accordion-item">
<h2 class="accordion-header" id="panelsStayOpen-headingOne">
<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true">
What is ewriters.co.ke?
</button>
</h2>
<div id="faq1" class="accordion-collapse collapse show">
<div class="accordion-body">
ewriters.co.ke is an online platform that offers writing and editing services to clients globally. We connect clients with a pool of professional freelancers who are experts in different fields.
</div>
</div>
</div>
<div class="accordion-item">
<h2 class="accordion-header" id="panelsStayOpen-headingTwo">
<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false">
What kind of writing services does ewriters.co.ke offer?
</button>
</h2>
<div id="faq2" class="accordion-collapse collapse">
<div class="accordion-body">
ewriters.co.ke offers a wide range of writing services, including academic writing, content writing, creative writing, technical writing, and business writing. We also offer editing and proofreading services to ensure that our clients' work is error-free and polished.

</div>
</div>
</div>
<div class="accordion-item">
<h2 class="accordion-header" id="panelsStayOpen-headingThree">
<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false">
How can I pay for services on ewriters.co.ke?
</button>
</h2>
<div id="faq3" class="accordion-collapse collapse">
<div class="accordion-body">
We offer two payment options: M-Pesa and PayPal. Our clients in Kenya can pay using M-Pesa, which is a mobile payment system widely used in the country. For international clients, we accept payments through PayPal, which is a secure and popular online payment platform.

</div>
</div>
</div>
<div class="accordion-item">
<h2 class="accordion-header" id="panelsStayOpen-headingFour">
<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="false">
How does ewriters.co.ke ensure the quality of the work delivered?
</button>
</h2>
<div id="faq4" class="accordion-collapse collapse">
<div class="accordion-body">
We have a rigorous screening process for our freelancers to ensure that they are qualified and experienced in their respective fields. We also have a quality assurance team that reviews each piece of work before delivery to ensure that it meets our clients' expectations and adheres to our quality standards.

</div>
</div>
</div>
<div class="accordion-item">
<h2 class="accordion-header" id="panelsStayOpen-headingFive">
<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5" aria-expanded="false">
Does ewriters.co.ke offer training to freelancers?
</button>
</h2>
<div id="faq5" class="accordion-collapse collapse">
<div class="accordion-body">
Yes, we offer training to our freelancers to ensure that they have the necessary skills and knowledge to deliver high-quality work to our clients. Our training program covers various topics, including writing techniques, formatting guidelines, plagiarism prevention, and communication skills.

</div>
</div>
</div>
</div>
<a href="/faqs"><button class="btn btn-primary" style="width:100%;">Read More faqs</button></a>
</div>
</section>

<?php
include 'footer.php';
?>