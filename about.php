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
<main id="main" class="main">
  <div class="pagetitle">
    <h1>About us</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">About</li>
      </ol>
    </nav>
  </div>

    <p style="font-size:1.3em;">
      Ewriters.co.ke is a leading freelancing company that specializes in providing high-quality writing and editing services to individuals and businesses around the world. The company has built a reputation for delivering exceptional work and reliable customer service, making it a top choice for clients seeking professional and timely writing assistance.
</br>
</br>
One of the standout features of ewriters.co.ke is its commitment to ensuring customer satisfaction. The company understands that clients come to them with specific needs and expectations, and it strives to meet and exceed those expectations on every project. From the initial consultation to the final delivery, ewriters.co.ke works closely with clients to ensure that their needs are met and that they are fully satisfied with the work that is produced.
</br>
</br>
In addition to its dedication to customer satisfaction, ewriters.co.ke is also known for its convenient payment options. The company accepts payments through M-Pesa and PayPal, two of the most popular and trusted payment platforms in the world. This allows clients to pay for their services quickly and securely, without having to worry about the safety of their financial information.
</br>
</br>
Ewriters.co.ke also offers a range of payment plans to suit the needs of its clients. Whether you need a one-time project completed or you require ongoing writing assistance, the company can work with you to create a payment plan that fits your budget and schedule. Additionally, ewriters.co.ke offers competitive rates for its services, ensuring that clients receive high-quality work at a fair and reasonable price.
</br>
</br>
Another factor that sets ewriters.co.ke apart from other freelancing companies is its partnerships with big companies. The company has established contracts with some of the most well-respected businesses in the world, providing writing and editing services for a wide range of industries and niches. This level of trust and reliability has helped ewriters.co.ke to build a reputation as a leading provider of professional writing services, and it continues to attract new clients from around the world.
</br>
</br>
Overall, ewriters.co.ke is a top choice for individuals and businesses seeking high-quality writing and editing services. With its commitment to customer satisfaction, convenient payment options, and competitive rates, the company has established itself as a trusted and reliable provider of professional writing services. Whether you need help with a one-time project or ongoing writing assistance, ewriters.co.ke has the expertise and experience to meet your needs and exceed your expectations.
    </p>

</main>
<?php
include 'footer.php';
?>