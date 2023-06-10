<?php
session_start();
include_once 'func.php';
if (!isset($_SESSION['e_id'])) {
  if (isset($_COOKIE['rememberme'])) {
    $_SESSION['e_id'] = decryptCookie($_COOKIE['rememberme']);
  }
}
include 'db.php'; 

$title = 'Frequently asked questions - Best Online Writing Jobs Marketplace. Get Online Writing Jobs or Hire Writers!';
include 'header.php';
?>

<div class="pagetitle">
    <h1>FAQs</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">FAQs</li>
      </ol>
    </nav>
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
<div class="accordion-body">ewriters.co.ke is an online platform that offers writing and editing services to clients globally. We connect clients with a pool of professional freelancers who are experts in different fields.
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
<div class="accordion-body">ewriters.co.ke offers a wide range of writing services, including academic writing, content writing, creative writing, technical writing, and business writing. We also offer editing and proofreading services to ensure that our clients' work is error-free and polished.

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
<div class="accordion-body">We offer two payment options: M-Pesa and PayPal. Our clients in Kenya can pay using M-Pesa, which is a mobile payment system widely used in the country. For international clients, we accept payments through PayPal, which is a secure and popular online payment platform.

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
<div class="accordion-body">We have a rigorous screening process for our freelancers to ensure that they are qualified and experienced in their respective fields. We also have a quality assurance team that reviews each piece of work before delivery to ensure that it meets our clients' expectations and adheres to our quality standards.

</div>
</div>
</div>
<div class="accordion-item">
<h2 class="accordion-header" id="panelsStayOpen-headingFive">
<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5" aria-expanded="false">
How does ewriters.co.ke handle revisions?
</button>
</h2>
<div id="faq5" class="accordion-collapse collapse">
<div class="accordion-body">We offer unlimited revisions to our clients at no extra cost. Our goal is to ensure that our clients are satisfied with the work delivered, and we are willing to make revisions until the client is happy with the final product.
</div>
</div>
</div>

<div class="accordion-item">
<h2 class="accordion-header" id="panelsStayOpen-headingFive">
<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6" aria-expanded="false">
Can I communicate directly with the freelancer working on my project?
</button>
</h2>
<div id="faq6" class="accordion-collapse collapse">
<div class="accordion-body">Yes, we encourage our clients to communicate directly with the freelancer working on their project. We have a messaging system on our platform that allows clients and freelancers to communicate in real-time.
</div>
</div>
</div>

<div class="accordion-item">
<h2 class="accordion-header" id="panelsStayOpen-headingFive">
<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq7" aria-expanded="false">
How does ewriters.co.ke handle plagiarism?
</button>
</h2>
<div id="faq7" class="accordion-collapse collapse">
<div class="accordion-body">We have a strict policy against plagiarism, and our freelancers are required to deliver original work. We use plagiarism detection tools to check each piece of work before delivery to ensure that it is original and free of plagiarism.
</div>
</div>
</div>

<div class="accordion-item">
<h2 class="accordion-header" id="panelsStayOpen-headingFive">
<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq8" aria-expanded="false">
How does ewriters.co.ke handle confidentiality?
</button>
</h2>
<div id="faq8" class="accordion-collapse collapse">
<div class="accordion-body">We take confidentiality seriously and have measures in place to protect our clients' information. We require our freelancers to sign a confidentiality agreement, and we do not share our clients' information with third parties.
</div>
</div>
</div>

<div class="accordion-item">
<h2 class="accordion-header" id="panelsStayOpen-headingFive">
<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq9" aria-expanded="false">
What makes ewriters.co.ke different from other writing service providers?
</button>
</h2>
<div id="faq9" class="accordion-collapse collapse">
<div class="accordion-body">We pride ourselves on our pool of professional freelancers who are experts in their respective fields. We also offer competitive pricing, unlimited revisions, and a user-friendly platform that makes it easy for clients to communicate with freelancers and track their projects' progress.
</div>
</div>
</div>

<div class="accordion-item">
<h2 class="accordion-header" id="panelsStayOpen-headingFive">
<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq10" aria-expanded="false">
Does ewriters.co.ke offer training to freelancers?
</button>
</h2>
<div id="faq10" class="accordion-collapse collapse">
<div class="accordion-body">Yes, we offer training to our freelancers to ensure that they have the necessary skills and knowledge to deliver high-quality work to our clients. Our training program covers various topics, including writing techniques, formatting guidelines, plagiarism prevention, and communication skills.
</div>
</div>
</div>

<div class="accordion-item">
<h2 class="accordion-header" id="panelsStayOpen-headingFive">
<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq10" aria-expanded="false">
How much does the training cost?
</button>
</h2>
<div id="faq10" class="accordion-collapse collapse">
<div class="accordion-body">We offer our training at an affordable cost to ensure that our freelancers can improve their skills without breaking the bank. The cost of the training program varies depending on the course's duration and complexity.
</div>
</div>
</div>

<div class="accordion-item">
<h2 class="accordion-header" id="panelsStayOpen-headingFive">
<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq11" aria-expanded="false">
What are the benefits of the training program?
</button>
</h2>
<div id="faq11" class="accordion-collapse collapse">
<div class="accordion-body">The training program offers several benefits to our freelancers, including improved writing skills, increased knowledge of different writing styles and formatting guidelines, and enhanced communication skills. It also helps our freelancers stay up-to-date with the latest trends in their respective fields.
</div>
</div>
</div>

<div class="accordion-item">
<h2 class="accordion-header" id="panelsStayOpen-headingFive">
<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq12" aria-expanded="false">
Who can enroll in the training program?
</button>
</h2>
<div id="faq12" class="accordion-collapse collapse">
<div class="accordion-body">The training program is open to all our freelancers who are interested in improving their skills and knowledge. We encourage our freelancers to take advantage of the training program to enhance their professional development and deliver high-quality work to our clients.
</div>
</div>
</div>

<div class="accordion-item">
<h2 class="accordion-header" id="panelsStayOpen-headingFive">
<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq13" aria-expanded="false">
How long does the training program last?
</button>
</h2>
<div id="faq13" class="accordion-collapse collapse">
<div class="accordion-body">The duration of the training program varies depending on the course's complexity and the freelancer's availability. We offer both short-term and long-term courses to accommodate our freelancers' schedules and preferences.
</div>
</div>
</div>

<div class="accordion-item">
<h2 class="accordion-header" id="panelsStayOpen-headingFive">
<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq14" aria-expanded="false">
How is the training program delivered?
</button>
</h2>
<div id="faq14" class="accordion-collapse collapse">
<div class="accordion-body">The training program is delivered online, and our freelancers can access the courses through our platform. We use various multimedia tools, including videos, webinars, and e-books, to deliver the training program.
</div>
</div>
</div>

<div class="accordion-item">
<h2 class="accordion-header" id="panelsStayOpen-headingFive">
<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq15" aria-expanded="false">
How does the training program benefit ewriters.co.ke clients?
</button>
</h2>
<div id="faq15" class="accordion-collapse collapse">
<div class="accordion-body">The training program benefits our clients by ensuring that our freelancers deliver high-quality work that meets their expectations. It also helps us maintain our reputation as a reliable and professional writing service provider.
</div>
</div>
</div>

<div class="accordion-item">
<h2 class="accordion-header" id="panelsStayOpen-headingFive">
<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq16" aria-expanded="false">
Can clients request trained freelancers for their projects?
</button>
</h2>
<div id="faq16" class="accordion-collapse collapse">
<div class="accordion-body">Yes, clients can request trained freelancers for their projects. We have a system in place that allows clients to view our freelancers' profiles and select the ones that meet their project requirements. Clients can also communicate directly with freelancers to discuss their project details and requirements.
</div>
</div>
</div>

</div>
</div>
</main>
<?php
include 'footer.php';
?>