<?php
session_start();
// connect to database
$servername = "localhost";
$dbusername = "ewriters_anon";
$dbpassword = "sfFKcjvsL9RcghJ";
$dbname = "ewriters_ewriters";

// create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// check connection
if ($conn->connect_error) {
  echo "Error connecting to database";
  exit;
}


function encryptCookie($value) {
  $key = hex2bin(openssl_random_pseudo_bytes(4));
  $cipher = "aes-256-cbc";
  $ivlen = openssl_cipher_iv_length($cipher);
  $iv = openssl_random_pseudo_bytes($ivlen);
  $ciphertext = openssl_encrypt($value, $cipher, $key, 0, $iv);
  return(base64_encode($ciphertext . '::' . $iv. '::' .$key));
}

function decryptCookie($ciphertext) {
  $cipher = "aes-256-cbc";
  list($encrypted_data, $iv, $key) = explode('::', base64_decode($ciphertext));
  return openssl_decrypt($encrypted_data, $cipher, $key, 0, $iv);
}

function getwithdrawals($conn, $id) {
  $sql = "SELECT * FROM withdrawals WHERE user=$id";
  return ($conn->query($sql));
}

function withdraw($amount, $id, $conn) {
  $sql = "SELECT balance FROM users WHERE id=$id";
  $result = $conn->query($sql);
  $row = mysqli_fetch_assoc($result);
  if ($row['balance'] > $amount) {
    $newbal = $row['balance']-$amount;
    $sql1 = "UPDATE users SET balance=$newbal WHERE id=$id";
    $sql2 = "INSERT INTO withdrawals (amount, user) VALUES ('$amount',$id)";
    if ((mysqli_query($conn, $sql1) === TRUE) && (mysqli_query($conn, $sql2) === TRUE)) {
      echo "success";
      exit;
    } else {
      echo "Error processing your request contact admin";
      exit;
    }
  } else {
    echo "Your account is too low to complete this transaction";
    exit;
  }
}

function mybalance($id, $conn) {
  $sql = "SELECT balance FROM users WHERE id ='$id'";
  $result = $conn->query($sql);
  $row =mysqli_fetch_assoc($result);
  return($row['balance']);
} 

function mybids($id, $conn) {
  $sql = "SELECT bids FROM users WHERE id ='$id'";
  $result = $conn->query($sql);
  $row =mysqli_fetch_assoc($result);
  return($row['bids']);
} 


function login($email, $password, $conn) {

  $sql = "SELECT * FROM users WHERE email = '$email'";
  $result = $conn->query($sql);
  if ($result->num_rows == 1) {
    $row = mysqli_fetch_array($result);
    if (password_verify($password, $row['password']) == TRUE) {
      $_SESSION['e_id'] = $row['id'];
      $userid = $row['id'];
      if (isset($_POST['rememberme'])) {
        $days = 30;
        $value = encryptCookie($userid);
        setcookie ("rememberme", $value, time()+ ($days * 24 * 60 * 60 * 1000));
      }
      echo "success";
      exit;
    } else {
      echo "Wrong password.";
    }
  } else {
    // Show an error message
    echo "Incorrect email address";
  }
}

if (isset($_POST['action'])) {
  if ($_POST["action"] == "login") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    login($email, $password, $conn);
  }


  if ($_POST["action"] == "resetcode") {
    $email = $_POST["email"];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$email) {
      echo "Invalid email address please type a valid email address!";
    } else {
      $sel_query = "SELECT * FROM `users` WHERE email='$email'";
      $sel_query2 = "SELECT * FROM `passreset` WHERE email='$email'";
      $results = mysqli_query($conn, $sel_query);
      $results2 = mysqli_query($conn, $sel_query2);
      $row = mysqli_num_rows($results);
      $row2 = mysqli_fetch_assoc($results2);
      $row3 = $results2->num_rows;
      if ($row == "") {
        echo "No user is registered with this email address!";
      } else {
        $expFormat = mktime(date("H"), date("i"), date("s"), date("m"), date("d")+1, date("Y"));
        $expDate = date("Y-m-d H:i:s", $expFormat);
        $key = substr(md5(uniqid(rand(), 1)), 3, 10);
        $key = $key;
        if ($row3 > 0) {
          $curDate = date("Y-m-d H:i:s");
          if ($curDate > ($row2['expiry'])) {
            $sql1 = "UPDATE `passreset` SET `key`='$key' WHERE email LIKE '$email'";
          } else {
            echo "Password reset link already sent. Check your mail including the Spam box.";
          }
        } else {
          $sql1 = "INSERT INTO `passreset` (`email`, `key`, `expiry`) VALUES ('$email', '$key', '$expDate')";
        }

        $output = '<p>Hello,</p>';
        $output .= '<p>Please click on the following link to reset your password.</p>';
        $output .= '<p>-------------------------------------------------------------</p>';
        $output .= '<p><a href="https://ewriters.co.ke/forgot-password?key='.$key.'&email='.$email.'&action=reset" target="_blank">
https://ewriters.co.ke/forgot-password?key='.$key.'&email='.$email.'&action=reset</a></p>';
        $output .= '<p>-------------------------------------------------------------</p>';
        $output .= '<p>Click on the link or copy the entire link into your browser.
The link will expire after 24 hours.</p>';
        $output .= '<p>If you did not request this forgotten password email, ignore this email, your password will not be reset.</p>';
        $output .= '<p>Thanks,</p>';
        $output .= '<p>ewriters.co.ke Support Team</p>';
        $body = $output;
        $subject = "Password Recovery";

        $email_to = $email;
        $fromserver = "admin@ewriters.co.ke";
        require 'phpmailer/src/Exception.php';
        require 'phpmailer/src/PHPMailer.php';
        require 'phpmailer/src/SMTP.php';
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->IsSMTP();
        $mail->Host = "mail.ewriters.co.ke";
        $mail->SMTPAuth = true;
        $mail->Username = "admin@ewriters.co.ke";
        $mail->Password = "K3mb0!J@pheth";
        $mail->Port = 587;
        $mail->IsHTML(true);
        $mail->From = "admin@ewriters.co.ke";
        $mail->FromName = "eWriters Support";
        $mail->Sender = $fromserver;
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AddAddress($email_to);
        $mail->SMTPSecure = 'tls';
        $mail->SMTPDebug = 0;
        if (!$mail->Send()) {
          echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
          if ($conn->query($sql1) === TRUE) {
            echo "success";
          } else {
            echo "Error sending reset link please contact developer";
          }
        }
      }
    }
  }

  if ($_POST["action"] == "editprofile") {
    $id = $_SESSION["e_id"];
    $f_name = $_POST["f_name"];
    $l_name = $_POST["l_name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $experience = $_POST["experience"];
    $olddp = $_POST["odp"];
    $about = nl2br(mysqli_real_escape_string($conn, $_POST["about"]));
    $skills = mysqli_real_escape_string($conn, $_POST["skills"]);

    if (isset($_FILES['dp'])) {
      if ($_FILES["dp"]["error"] == UPLOAD_ERR_OK) {
        $target_dir = "/home/ewriters/public_html/dp/";
        $target_file = $target_dir.uniqid().basename($_FILES["dp"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $max_file_size = 3 * 1024 * 1024; // 3MB in bytes

        // Check if the file is an image and is smaller than 3MB
        if (getimagesize($_FILES["dp"]["tmp_name"]) !== false && $_FILES["dp"]["size"] <= $max_file_size && ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif")) {

          // Move the file to the server directory
          if (move_uploaded_file($_FILES["dp"]["tmp_name"], $target_file)) {
            if (($olddp) && ($olddp != "default")) {
              $file_path = 'dp/'.$olddp;
              if (file_exists($file_path)) {
                unlink($file_path);
              }
            }
            $dp = $target_file;
          } else {
            echo "Sorry, there was an error uploading your file.";
          }
        } else {
          echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed and must be less than 3MB.";
        }
      }
    } else {
      $dp = "default";
    }
    $sql = "UPDATE users SET f_name='$f_name', l_name='$l_name', email='$email', phone='$phone', experience='$experience', skills='$skills',  about='$about', dp='$dp' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
      $sql2 = "SELECT * FROM users WHERE email = '$email'";
      $result2 = $conn->query($sql2);
      if ($result2->num_rows == 1) {
        $row2 = mysqli_fetch_array($result2);
        $_SESSION['e_id'] = $row2['id'];
        echo "success";
      }
    } else {
      echo "Error updating your data";
    }
  }

  if ($_POST["action"] == "change-pass") {
    $pass1 = mysqli_real_escape_string($conn, $_POST["pass1"]);
    $pass2 = mysqli_real_escape_string($conn, $_POST["pass2"]);
    $email = $_POST["email"];
    $curDate = date("Y-m-d H:i:s");
    if ($pass1 != $pass2) {
      echo "Your passwords don't match.";
      exit;
    } else {
      $pass = password_hash($pass1, PASSWORD_DEFAULT);
      $sql = "UPDATE users SET password='$pass' WHERE email='$email'";
      $sql1 = "DELETE FROM passreset WHERE email='$email'";
      if (($conn->query($sql) === TRUE) && ($conn->query($sql1) === TRUE)) {
        echo 'success';
      } else {
        echo "Error updating you password";
      }
    }
  }

  if ($_POST["action"] == "getproject") {
    $id = $_POST["id"];
    $sql = "SELECT * FROM projects WHERE id = '$id'";
    $result = $conn->query($sql);
    // If email and password are correct
    if ($result->num_rows == 1) {
      $row = mysqli_fetch_array($result);
      echo "<script>
    var id = ".$row['id']."; var title = ".$row['title'].";
  </script>
  ";
    } else {
      // Show an error message
      echo "Incorrect email address";
    }
  }


  if ($_POST["action"] == "logout") {
    session_unset();
    session_destroy();
    $days = 30;
    setcookie ("rememberme", "", time() - ($days * 24 * 60 * 60 * 1000));
    echo "success";
  }


  if ($_POST["action"] == "post") {
    session_start();
    $owner = $_SESSION['e_id'];
    $title = mysqli_escape_string($conn, $_POST['title']);
    $desc = mysqli_escape_string($conn, $_POST['description']);
    $type = $_POST['type'];
    $budget = $_POST['budget'];

    $sql = "INSERT INTO projects (owner_id, title, description, type, budget) VALUES ('$owner', '$title', '$desc', '$type', '$budget')";

    if ($conn->query($sql) === TRUE) {
      echo "success";
    } else {
      echo "Error posting your project please contact developer";
    }
  }


  if ($_POST["action"] == "bid") {
    session_start();
    $bidder = $_SESSION['e_id'];
    $project = $_SESSION['pid'];
    $bid = $_POST['bid'];
    $budget = $_POST['budget'];

    $sql = "INSERT INTO bids (project, bidder, budget, bid) VALUES ('$project', '$bidder', '$budget', '$bid')";
    $sql1 = "SELECT * FROM users WHERE id = '$bidder'";
    $result1 = $conn->query($sql1);
    if ($result1->num_rows == 1) {
      $row1 = mysqli_fetch_assoc($result1);
      if ($row1['bids'] > 0) {
        $newbids = $row1['bids']-1;
        $query = "UPDATE users SET bids = '$newbids' WHERE id = '$bidder'";
        if (($conn->query($sql) === TRUE) && ($conn->query($query))) {
          echo "success";
        } else {
          echo "Error posting your bid please contact developer.";
        }
      } else {
        echo "You are out of bids.";
      }
    } else {
      echo "Invalid user";
    }
  }


  if ($_POST["action"] == "completed") {
    $project = $_POST['project'];
    $owner = $_POST['owner'];
    $winner = $_POST['winner'];

    $sql1 = "UPDATE projects SET status = 'Completed' WHERE id =$project";

    $query3 = "INSERT INTO notifications (sender, recipient, message) VALUES (22,".$winner.",'Hello, you have successfully completed working on project id ".$project." <a href=\'https://ewriters.co.ke/p?".$project."\'>https://ewriters.co.ke/p?".$project."</a>. Payment will be made to your account. Keep on bidding lad!')";
    $query4 = "INSERT INTO notifications (sender, recipient, message) VALUES (22,".$owner.",'Hello, you have marked your project as Completed. Project id ".$project.". I hope you were satisfied with the project. Keep on posting projects.')";
    mysqli_query($conn, $query3);
    mysqli_query($conn, $query4);
    mysqli_query($conn, $sql1);
    echo "success";
  }

  if ($_POST["action"] == "hire") {
    $winner = $_POST['bidder'];
    $project = $_POST['project'];

    $sql = "UPDATE bids SET status = 'Won' WHERE bidder=$winner AND project=$project";
    $sql1 = "UPDATE projects SET status = 'In progress' WHERE id =$project";

    $query3 = "INSERT INTO notifications (sender, recipient, message) VALUES (22,".$winner.",'Hello, you have been chosen to work on project id ".$project.". Click this link to view more details. <a href=\'https://ewriters.co.ke/p?".$project."\'>https://ewriters.co.ke/p?".$project."</a>)";
    mysqli_query($conn, $query3);
    mysqli_query($conn, $sql);
    mysqli_query($conn, $sql1);
    echo "success";
  }


  if ($_POST["action"] == "purchase") {
    session_start();
    $owner = $_SESSION['e_id'];
    $amount = $_POST["amount"];
    $bal = mybalance($owner, $conn);
    switch ($amount) {
        case 200:
          $bids = 10;
          break;
        case 350;
          $bids = 20;
          break;
        case 500:
          $bids = 35;
          break;
        case 750:
          $bids = 50;
          break;
      }
    if($bal >= $amount){
      if(isset($bids)){
      $newbal = $bal-$amount;
      $newbids = mybids($owner, $conn)+$bids;
      $query = "UPDATE users SET bids = $newbids, balance = $newbal WHERE id=$owner";
      if ($conn->query($query) === TRUE) {
            echo "success";
      }
     }else{echo "Error occurred";}
    }else{
      echo "You do not have sufficient balance to purchase this package. Please add funds to continue.";
    }
    
  } 
 
  if ($_POST["action"] == "deposit") {
    session_start();
    $owner = $_SESSION['e_id'];
    $code = $_POST["mpesacode"];

    $sql = "SELECT * FROM codes WHERE id LIKE '$code'";
    $result = $conn->query($sql);
    // If email and password are correct
    if ($result->num_rows == 1) {
      $row = mysqli_fetch_array($result);
      if ($row['id'] === "$code") {
        if ($row['used'] == "No") {
          $sql1 = "SELECT * FROM users WHERE id=$owner";
          $result1 = $conn->query($sql1);
          $row1 = mysqli_fetch_assoc($result1);
          $bal = $row['amount']+$row1['balance'];
          $query = "UPDATE users SET balance=$bal WHERE id=$owner";
          $query2 = "UPDATE codes SET used = 'Yes' WHERE id =$code";
          // Execute the query
          if (($conn->query($query2) === TRUE) && ($conn->query($query) === TRUE)) {
            echo "success";
          } else {
            echo "Please try again. If problem persists contact us. ";
          }
        } else {
          echo "Invalid Transaction Code";
        }
      } else {
        echo "Invalid Transaction Code";
      }
    } else {
      echo "Invalid Transaction Code. Wait a few minutes after making deposit.";
    }
  }


  if ($_POST["action"] == "activate") {
    session_start();
    $owner = $_SESSION['e_id'];
    $code = $_POST["mpesacode"];

    $sql = "SELECT * FROM codes WHERE id LIKE '$code'";

    $result = $conn->query($sql);
    // If email and password are correct
    if ($result->num_rows == 1) {
      $row = mysqli_fetch_array($result);
      if ($row['id'] === "$code") {
        if ($row['used'] == "No") {
         if($row['amount']>499){
          $sql2 = "SELECT * FROM users WHERE id=$owner";
          $results2 = $conn->query($sql2);
          $row2 = mysqli_fetch_assoc($results2);
          if ($row2['ref'] == 0) {} else {
            $ref = $row2['ref'];
            $sql4 = "SELECT balance FROM users WHERE id=$ref";
            $result4 = $conn->query($sql4);
            $row4 = mysqli_fetch_assoc($result4);
            $bal = $row4['balance']+100;
            $query3 = "INSERT INTO notifications (sender, recipient, message) VALUES (22,".$ref.",'Hello, you have received Ksh 100 referral credit. Keep referring to earn more. Your referral link is <a href=\'https://ewriters.co.ke/register?a=".$ref."\'>https://ewriters.co.ke/register/?a=".$ref."</a> copy and share.')";
            mysqli_query($conn, $query3);
            $query1 = "UPDATE users SET balance =".$bal." WHERE id =$ref";
            mysqli_query($conn, $query1);
          }
          $query = "UPDATE users SET active = 'Yes' WHERE id=$owner";
          $query2 = "UPDATE codes SET used = 'Yes' WHERE id =$code";
          // Execute the query
          if (($conn->query($query2) === TRUE) && ($conn->query($query) === TRUE)) {
            $_SESSION['e_active'] = 'Yes';
            echo "success";
          } else {
            echo "Please try again. If problem persists contact us. ";
          }
        } else{
          echo "Please deposit Ksh 500.";
        }
        } else {
          echo "Invalid Transaction Code";
        }
      } else {
        echo "Invalid Transaction Code";
      }
    } else {
      echo "Invalid Transaction Code";
    }
  }


  if ($_POST["action"] == "withdraw") {
    withdraw($_POST['amount'], $_SESSION['e_id'], $conn);
  }


  if ($_POST["action"] == "register") {
    // retrieve form data
    $f_name = $_POST["f_name"];
    $l_name = $_POST["l_name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
    $type = $_POST["type"];
    // validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      echo "Invalid email address";
      exit;
    }

    // check if user already exists
    $sql = "SELECT email FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      echo "User already exists. Log in instead.";
      exit;
    }
    if (isset($_POST['ref'])) {
      $ref = $_POST['ref'];
    } else {
      $ref = 0;
    }

    // hash password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (f_name, l_name, email, phone, password, type, ref) VALUES ('$f_name', '$l_name', '$email', '$phone', '$hashedPassword', '$type', $ref)";

    if ($conn->query($sql) === TRUE) {
      echo "success";
    } else {
      echo "Error connecting to database please try again.".mysqli_error($conn);
    }
  }
}

function isactive($id, $conn) {
  $sql = "SELECT active FROM users WHERE id=$id";
  $result = mysqli_query($conn, $sql);
  if ($result->num_rows == 1) {
    $row = mysqli_fetch_array($result);
    return $row['active'];
  }
}