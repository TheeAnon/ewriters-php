<?php
session_start();
include_once 'func.php';
if (!isset($_SESSION['e_id'])) {
  if (isset($_COOKIE['rememberme'])) {
    $_SESSION['e_id'] = decryptCookie($_COOKIE['rememberme']);
  }
}
include 'db.php'; 

$title = 'Training Program - From nerd to pro. Best Online Writing Jobs Marketplace. Get Online Writing Jobs or Hire Writers!';
include 'header.php';
?>
<main id="main" class="main">
 <div class="pagetitle">
    <h1>Training program</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Training</li>
      </ol>
    </nav>
  </div>

<p>
  Welcome to the training program section of ewriters.co.ke, where you can hone your writing skills and improve your chances of success in the freelance writing industry. Our training program is designed to equip you with the necessary tools and knowledge to become a successful freelance writer.</p>
<p>Our training program is modeled after successful programs, which has helped many aspiring writers become successful in their writing careers. Our program is designed to be interactive and engaging, with a focus on practical exercises that help you develop your writing skills.</p>
<p>To participate in our training program, there is a fee of Ksh 500. This fee covers the cost of course materials, as well as access to our team of experienced writing instructors who will provide you with guidance and support throughout the program.</p>
<b>Our training program covers a wide range of topics, including:</b>
<br/>
<p><b>Writing fundamentals:</b> We will cover the basics of writing, including grammar, sentence structure, and paragraph development.</p>
<p><b>Writing for the web:</b> We will explore the nuances of writing for the web, including how to optimize your content for search engines and how to write engaging content that keeps readers coming back for more.</p>
<p><b>Writing for different niches:</b> We will provide you with guidance on how to write for different niches, including business, technology, and health.</p>
<p><b>Freelancing basics:</b> We will cover the basics of freelancing, including how to find clients, how to set your rates, and how to build a portfolio.</p>
<p>At the end of the training program, you will have the skills and knowledge to become a successful freelance writer. You will also have access to our network of clients who are looking for talented writers to work on their projects.</p>
<p>We are committed to helping you achieve your goals as a freelance writer, and we look forward to working with you in our training program.
</p>
<br/>
<b>Contact us on email admin@ewriters.co.ke or <a href="https://wa.me/254768836150">WhatsApp</a> to enroll today. </b>

</main>
<?php
include 'footer.php';
?>