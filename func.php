<?php
session_start();
include 'db.php';
function getNotifications($conn, $id) {
  $sql = $conn->prepare("SELECT * FROM notifications WHERE recipient=:id");
  $sql->bindParam(':id', $id);
  $sql->execute();
  return $sql;
}

function getNewNotifications($conn, $id) {
  $sql = $conn->prepare("SELECT * FROM notifications WHERE recipient=:id AND status LIKE 'Sent'");
  $sql->bindParam(':id', $id);
  $sql->execute();
  return $sql;
}

function getMessages($conn, $id) {
  $sql = $conn->prepare("SELECT * FROM messages WHERE sender=:id or recipient=:id");
  $sql->bindParam(':id', $id);
  $sql->execute();
  return $sql;
}

function getNewMessages($conn, $id) {
  $sql = $conn->prepare("SELECT * FROM messages WHERE sender=:id or recipient=:id AND status LIKE 'Sent'");
  $sql->bindParam(':id', $id);
  $sql->execute();
  return $sql;
}

function sendNotification($conn, $id, $notification) {
  $sql = $conn->prepare("INSERT INTO notifications (recipient, notification) VALUES (:id, :notification)");
  $sql->bindParam(':id', $id);
  $sql->bindParam(':notification', $notification);
  $sql->execute();
  echo 'success';
}

function sendMessage($conn, $sender, $recipient, $message) {
  $sql = $conn->prepare("INSERT INTO messages (sender, recipient, message) VALUES (:sender, :recipient, :message)");
  $sql->bindParam(':sender', $sender);
  $sql->bindParam(':recipient', $recipient);
  $sql->bindParam(':message', $message);
  $sql->execute();
  echo 'success';
}

function completeProfile($conn, $id, $skills, $experience, $about, $dp) {
  $sql = $conn->prepare("INSERT INTO profiles (id, experience, skills, about, dp) VALUES (:id, :experience, :skills, :about, :dp)");
  $sql->bindParam(':id', $id);
  $sql->bindParam(':experience', $experience);
  $skill = implode(",", $skills);
  $sql->bindParam(':skills', $skill);
  $sql->bindParam(':about', $about);
  if (!empty($dp["name"])) {
    $targetDir = "dp/";
    $fileName = "dp".time()."-".rand(1000, 9999)."-".$dp['name'];
    $targetFilePath = $targetDir.$fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'pdf');
    if (in_array($fileType, $allowTypes)) {
      if ($dp["size"] > 3000000) {
        echo "Sorry, your file is too large. Choose a smaller image.";
      }
      if (move_uploaded_file($dp["tmp_name"], $targetFilePath)) {
        $fileName = $fileName;
      } else {
        echo "File upload failed, please try again.";
        exit;
      }
    }
  } else {
    $fileName = 'default.png';
  }
  $sql->bindParam(':dp', $fileName);
  $sql->execute();
  echo 'success';
}

function register($conn, $f_name, $l_name, $email, $phone, $password, $pass2, $type) {
  $check = $conn->prepare("SELECT * FROM users WHERE email=:email");
  $sql = $conn->prepare("INSERT INTO users (f_name, l_name, email, phone, password, type) VALUES (:f_name, :l_name, :email, :phone, :hash_password, :type)");
  $account = $conn->prepare("INSERT INTO account (id, bids) VALUES (:id, :bids)");

  $check->bindParam(':email', $email);
  $check->execute();
  if ($check->rowCount() > 0) {
    echo "User already exists. Log in instead.";
    exit;
  }
  if ($pass2 !== $password) {
    echo "Your passwords don't match.";
    exit;
  }

  $hash_password = password_hash($password, PASSWORD_DEFAULT);
  $sql->bindParam(':f_name', $f_name);
  $sql->bindParam(':l_name', $l_name);
  $sql->bindParam(':email', $email);
  $sql->bindParam(':phone', $phone);
  $sql->bindParam(':hash_password', $hash_password);
  $sql->bindParam(':type', $type);
  $sql->execute();
  $check->bindParam(':email', $email);
  $check->execute();
  $select = $check->fetchAll(PDO::FETCH_ASSOC);
  if ($check->rowCount() == 1) {
    foreach ($select as $row) {
      $account->bindParam(':id', $row['id']);
      $account->bindValue(':bids', 20);
      $account->execute();
    }
    login($conn, $email, $password);
  } else {
    echo "Your account has issues. Contact admin@ewriters.co.ke to resolve.";
  }
}

function login($conn, $email, $password) {
  $sql = $conn->prepare("SELECT * FROM users WHERE email=:email");
  $sql->bindParam(':email', $email);
  $sql->execute();
  if ($sql->rowCount() == 1) {
    $result = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
      if (password_verify($password, $row['password']) == TRUE) {
        $_SESSION['e_id'] = $row['id'];
        $userid = $row['id'];
        if (isset($_POST['rememberme'])) {
          $value = encryptCookie($userid);
          setcookie("rememberme", $value, time()+ (30 * 24 * 60 * 60 * 1000));
        }
        echo "success";
      } else {
        echo "Wrong password.";
      }
    }
  } else {
    echo "Your account is not registered. Sign up to continue.";
  }
}

function formatTime($date) {
  $timestamp = strtotime($date);
  echo date('j F, Y', $timestamp);
}

function formatDate($date) {
  $timestamp = strtotime($date);
  return date('j F, Y', $timestamp);
}

function getNames($conn, $id) {
  $sql = $conn->prepare("SELECT f_name, l_name FROM users WHERE id=:id");
  $sql->bindParam(':id', $id);
  $sql->execute();
  $result = $sql->fetchAll(PDO::FETCH_ASSOC);
  foreach ($result as $row) {
    $names = $row['f_name']." ".$row['l_name'];
    return $names;
  }
}

function getProjects($conn, $limit, $employer) {
  if ($employer != 'None') {
    $e = "WHERE employer=$employer";
  } else {
    $e = "";
  }
  if ($limit == 'All') {
    $sql = $conn->prepare("SELECT * FROM projects ".$e." ORDER BY date DESC");
  } else {
    $sql = $conn->prepare("SELECT * FROM projects ".$e." ORDER BY date DESC LIMIT :limit");
    $sql->bindParam(':limit', $limit, PDO::PARAM_INT);
  }
  $sql->execute();
  return $sql;
}

function getEmployerID($conn, $id) {
  $sql1 = $conn->prepare("SELECT employer FROM projects WHERE id=:id");
  $sql1->bindParam(':id', $id, PDO::PARAM_INT);
  $sql1->execute();
  $id = $sql1->fetchColumn();
  return $id;
}


function getProjectsFreelancer($conn, $id) {
  $sql1 = $conn->prepare("SELECT project FROM completed_projects WHERE freelancer=:freelancer");
  $sql1->bindParam(':freelancer', $id, PDO::PARAM_INT);
  $sql1->execute();
  $project = $sql1->fetchColumn();
  $sql = $conn->prepare("SELECT * FROM projects WHERE id=:id ORDER BY date DESC");
  $sql->bindParam(':id', $project, PDO::PARAM_INT);
  $sql->execute();
  return $sql;
}

function getProjectsES($conn, $status, $employer) {
  if ($employer == 'None') {
    if ($status == 'All') {
      $sql = $conn->prepare("SELECT * FROM projects ORDER BY date DESC");
    } else if ($status == 'Completed') {
      $sql = $conn->prepare("SELECT * FROM projects WHERE (status LIKE 'In progress' OR status LIKE 'Completed') ORDER BY date DESC");
    } else {
      $sql = $conn->prepare("SELECT * FROM projects WHERE status LIKE :status ORDER BY date DESC");
      $sql->bindParam(':status', $status);
    }
  } else if ($status == 'All') {
    $sql = $conn->prepare("SELECT * FROM projects WHERE employer=:employer ORDER BY date DESC");
    $sql->bindParam(':employer', $employer, PDO::PARAM_INT);
  } else {
    if ($status == 'Completed') {
      $sql = $conn->prepare("SELECT * FROM projects WHERE employer=:employer AND (status LIKE 'In progress' OR status LIKE 'Completed') ORDER BY date DESC");
      $sql->bindParam(':employer', $employer, PDO::PARAM_INT);
    } else {
      $sql = $conn->prepare("SELECT * FROM projects WHERE employer =:employer AND status LIKE :status ORDER BY date DESC");
      $sql->bindParam(':status', $status);
      $sql->bindParam(':employer', $employer, PDO::PARAM_INT);
    }
  }
  $sql->execute();
  return $sql;
}

function getProjectsStatus($conn, $status) {
  if ($status == 'All') {
    $sql = $conn->prepare("SELECT * FROM projects ORDER BY date DESC");
  } else {
    $sql = $conn->prepare("SELECT * FROM projects WHERE status LIKE :status ORDER BY date DESC LIMIT :limit");
    $sql->bindParam(':status', $status);
  }
  $sql->execute();
  return $sql;
}

function getProject($conn, $id) {
  try {
    $sql = $conn->prepare("SELECT * FROM projects WHERE id = :id");
    $sql->bindParam(':id', $id);
    $sql->execute();
    $result = $sql->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (Exception $e) {
    echo '<script>window.location.assign("/gigs")';
  }
}

function getProjectsDone($conn, $id) {
  $sql = $conn->prepare("SELECT * FROM completed_projects WHERE freelancer = :id");
  $sql->bindParam(':id', $id);
  $sql->execute();
  return $sql;
}
function getTotalEarnings($conn, $id) {
  $sql = $conn->prepare("SELECT * FROM completed_projects WHERE freelancer=:id");
  $sql->bindParam(':id', $id);
  $sql->execute();
  $earnings = 0;
  foreach ($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $earnings += $row['budget'];
  }
  return $earnings;
}

function getUsers($conn, $type, $sort, $limit) {
  if ($limit == 0) {
    $sql = $conn->prepare("SELECT * FROM users WHERE type LIKE :type ORDER BY :sort DESC");
  } else {
    $sql = $conn->prepare("SELECT * FROM users WHERE type LIKE :type ORDER BY :sort DESC LIMIT :limit");
    $sql->bindParam(':limit', $limit, PDO::PARAM_INT);
  }
  $sql->bindParam(':type', $type);
  $sql->bindParam(':sort', $sort);
  $sql->execute();
  $result = $sql->fetchAll(PDO::FETCH_ASSOC);
  return $result;
}

function getUser($conn, $id) {
  $sql = $conn->prepare("SELECT * FROM users WHERE id=:id");
  $sql->bindParam(':id', $id);
  $sql->execute();
  $result = $sql->fetchAll(PDO::FETCH_ASSOC);
  return $result;
}

function getAccType($conn, $id) {
  $sql = $conn->prepare("SELECT type FROM users WHERE id=:id");
  $sql->bindParam(':id', $id);
  $sql->execute();
  $result = $sql->fetchColumn();
  return $result;
}

function getProfile($conn, $id) {
  $sql = $conn->prepare("SELECT * FROM profiles WHERE id=:id");
  $sql->bindParam(':id', $id);
  $sql->execute();
  $result = $sql->fetchAll(PDO::FETCH_ASSOC);
  return $result;
}

function getAccount($conn, $id) {
  $sql = $conn->prepare("SELECT * FROM account WHERE id=:id");
  $sql->bindParam(':id', $id);
  $sql->execute();
  $result = $sql->fetchAll(PDO::FETCH_ASSOC);
  return $result;
}

function getDp($conn, $id) {
  $sql = $conn->prepare("SELECT dp FROM profiles WHERE id=:id");
  $sql->bindParam(':id', $id, PDO::PARAM_INT);
  $sql->execute();
  $dp = $sql->fetchColumn();
  if ($dp) {
    return $dp;
  } else {
    echo 'default.png';
  }
}

function getRating($conn, $id) {
  $sql = $conn->prepare("SELECT rating FROM profiles WHERE id=:id");
  $sql->bindParam(':id', $id, PDO::PARAM_INT);
  $sql->execute();
  $rating = $sql->fetchColumn();
  return $rating;
}

function getBalance($conn, $id) {
  if (null == getAccount($conn, $id)) {
    $account = $conn->prepare("INSERT INTO account (id, bids) VALUES (:id, :bids)");
    $account->bindParam(':id', $id, PDO::PARAM_INT);
    $account->bindValue(':bids', 20);
    $account->execute();
  }
  $sql = $conn->prepare("SELECT balance FROM account WHERE id=:id");
  $sql->bindParam(':id', $id, PDO::PARAM_INT);
  $sql->execute();
  $bal = $sql->fetchColumn();
  return $bal;
}

function getBids($conn, $id) {
  $sql = $conn->prepare("SELECT bids FROM account WHERE id=:id");
  $sql->bindParam(':id', $id, PDO::PARAM_INT);
  $sql->execute();
  $bids = $sql->fetchColumn();
  return $bids;
}


function getCompleteProjects($conn, $id, $status) {
  if ($status == 'Completed') {
    $sql = $conn->prepare("SELECT * FROM completed_projects WHERE freelancer=:id AND (status LIKE 'Paid' OR status LIKE 'Not Paid')");
  } else if ($status == 'All') {
    $sql = $conn->prepare("SELECT * FROM completed_projects WHERE freelancer=:id");
  } else {
    $sql = $conn->prepare("SELECT * FROM completed_projects WHERE freelancer=:id AND status LIKE :status");
    $sql->bindParam(':status', $status);
  }
  $sql->bindParam(':id', $id, PDO::PARAM_INT);
  $sql->execute();
  return $sql;
}

function getProjectBids($conn, $project, $freelancer, $status) {
  if ($status == 'None') {
    $status1 = "";
  } else {
    $status1 = "AND status LIKE $status";
  }
  if (($project == 'All') && ($freelancer != 'All')) {
    $sql = $conn->prepare("SELECT * FROM bids WHERE freelancer=:freelancer".$status1);
    $sql->bindParam(':freelancer', $freelancer, PDO::PARAM_INT);
  }
  if (($project != 'All') && ($freelancer == 'All')) {
    $sql = $conn->prepare("SELECT * FROM bids WHERE project=:project".$status1);
    $sql->bindParam(':project', $project, PDO::PARAM_INT);
  }
  if (($project != 'All') && ($freelancer != 'All')) {
    $sql = $conn->prepare("SELECT * FROM bids WHERE project=:project AND freelancer=:freelancer");
    $sql->bindParam(':project', $project, PDO::PARAM_INT);
    $sql->bindParam(':freelancer', $freelancer, PDO::PARAM_INT);
  }
  $sql->execute();
  return $sql;
}

function getWithdrawals($conn, $id, $status) {
  if ($status == 'All') {
    $sql = $conn->prepare("SELECT * FROM withdrawals WHERE user=:id");
  } else {
    $sql = $conn->prepare("SELECT * FROM withdrawals WHERE user=:id AND status LIKE :status");
    $sql->bindParam(':status', $status);
  }
  $sql->bindParam(':id', $id, PDO::PARAM_INT);
  $sql->execute();
  return $sql;
}

function getDeposits($conn, $id) {
  $sql = $conn->prepare("SELECT * FROM deposits WHERE user=:id");
  $sql->bindParam(':id', $id, PDO::PARAM_INT);
  $sql->execute();
  return $sql;
}

function getPurchases($conn, $id) {
  $sql = $conn->prepare("SELECT * FROM purchases WHERE user=:id");
  $sql->bindParam(':id', $id, PDO::PARAM_INT);
  $sql->execute();
  return $sql;
}


function encryptCookie($value) {
  $key = bin2hex(openssl_random_pseudo_bytes(4));
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

function logout() {
  session_destroy();
  $days = 30;
  setcookie ("rememberme", "", time() - ($days * 24 * 60 * 60 * 1000));
  echo "<script>window.location.assign('/');</script>";
}


function postBid($conn, $freelancer, $budget, $bid, $project) {
  if (getBids($conn, $freelancer) > 0) {
    $bids = getBids($conn, $freelancer)-1;
    $update = $conn->prepare("UPDATE account SET bids=:bids WHERE id=:id");
    $update->bindParam(':bids', $bids, PDO::PARAM_INT);
    $update->bindParam(':id', $freelancer, PDO::PARAM_INT);
    $update->execute();
    $sql = $conn->prepare("INSERT INTO bids (freelancer, project, bid, budget) VALUES (:freelancer, :project, :bid, :budget)");
    $sql->bindParam(':freelancer', $freelancer, PDO::PARAM_INT);
    $sql->bindParam(':project', $project, PDO::PARAM_INT);
    $sql->bindParam(':budget', $budget, PDO::PARAM_INT);
    $sql->bindParam(':bid', $bid);
    $sql->execute();
    echo 'success';
  } else {
    echo 'You do not have bids to bid on this project. Purchase bids to continue.';
  }
}


function deposit($conn, $id, $code) {
  $sql = $conn->prepare("SELECT * FROM codes");
  $sql->execute();
  foreach ($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
    if (($row['id'] == $code) && ($row['used'] == 'No')) {
      $bal = getBalance($conn, $id);
      $newbal = $row['amount']+$bal;
      $update = $conn->prepare("UPDATE account SET balance=:bal WHERE id=:id");
      $update->bindParam(':bal', $newbal, PDO::PARAM_INT);
      $update->bindParam(':id', $id, PDO::PARAM_INT);
      $update->execute();
      $update2 = $conn->prepare("UPDATE codes SET used=:used WHERE id=:id");
      $update2->bindValue(':used', 'Yes');
      $update2->bindParam(':id', $code, PDO::PARAM_INT);
      $update2->execute();
      $insert = $conn->prepare("INSERT INTO deposits (user, amount, method) VALUES (:user, :amount, :method)");
      $insert->bindParam(':user', $id, PDO::PARAM_INT);
      $insert->bindParam(':amount', $row['amount'], PDO::PARAM_INT);
      $insert->bindValue(':method', 'Mpesa');
      $insert->execute();
      echo 'success';
    } else {
      echo 'Invalid Transaction Code';
    }
  }
}


function withdraw($conn, $id, $amount) {
  if ($amount < 1000) {
    echo 'The minimum withdrawal amount is Ksh 1000.';
    exit;
  }
  if (getBalance($conn, $id) >= $amount) {
    $bal = getBalance($conn, $id);
    $newbal = $bal-intval($amount);
    $update = $conn->prepare("UPDATE account SET balance=:bal WHERE id=:id");
    $update->bindParam(':bal', $newbal, PDO::PARAM_INT);
    $update->bindParam(':id', $id, PDO::PARAM_INT);
    $update->execute();
    $update2 = $conn->prepare("INSERT INTO withdrawals (amount, user, method) VALUES (:amount, :user, :method)");
    $update2->bindValue(':method', 'Mpesa');
    $update2->bindParam(':user', $id, PDO::PARAM_INT);
    $update2->bindParam(':amount', $amount, PDO::PARAM_INT);
    $update2->execute();
    echo 'success';
  } else {
    echo 'Your account balance is too small to carry out this transaction.';
  }
}

function buyBids($conn, $id, $amount) {
  switch ($amount) {
    case 200:
      $package = 'Starter Package';
      $amount = 200;
      $bids = 10;
      break;
    case 350:
      $package = 'Freelancer plus';
      $amount = 350;
      $bids = 20;
      break;
    case 500:
      $package = 'Freelancer Active';
      $amount = 500;
      $bids = 35;
      break;
    case 750:
      $package = 'Agency';
      $amount = 750;
      $bids = 50;
      break;
    default:
      $none = 'none';
      break;
  }

  if (!isset($none)) {
    if (getBalance($conn, $id) < $amount) {
      echo 'Your account balance is too low to complete this transaction.';
      exit;
    } else {
      $newbal = getBalance($conn, $id)-$amount;
      $newbids = getBids($conn, $id)+$bids;
      $update = $conn->prepare("UPDATE account SET balance=:bal, bids=:bids WHERE id=:id");
      $update->bindParam(':bal', $newbal, PDO::PARAM_INT);
      $update->bindParam(':id', $id, PDO::PARAM_INT);
      $update->bindParam(':bids', $newbids, PDO::PARAM_INT);
      $update->execute();
      $update2 = $conn->prepare("INSERT INTO purchases (user, package, amount) VALUES (:user, :package, :amount)");
      $update2->bindParam(':package', $package);
      $update2->bindParam(':user', $id, PDO::PARAM_INT);
      $update2->bindParam(':amount', $amount, PDO::PARAM_INT);
      $update2->execute();
      echo 'success';
    }
  } else {
    echo 'Something went wrong.';
    exit;
  }
}


function postProject($conn, $employer, $title, $budget, $category, $description, $draft) {
  if ($draft == 'true') {
    $draft = $conn->prepare("INSERT INTO projects (employer, title, description, category, budget, status) VALUES (:employer, :title, :description, :category, :budget, :status)");
    $draft->bindParam(':title', $title);
    $draft->bindParam(':description', $description);
    $draft->bindParam(':category', $category);
    $draft->bindParam(':budget', $budget, PDO::PARAM_INT);
    $draft->bindParam(':employer', $employer, PDO::PARAM_INT);
    $draft->bindValue(':status', 'draft');
    $draft->execute();
    echo 'success';
    exit();
  } else {
    if (getAccType($conn, $employer) == "Admin") {
      $sql = $conn->prepare("INSERT INTO projects (employer, title, description, category, budget) VALUES (:employer, :title, :description, :category, :budget)");
      $sql->bindParam(':title', $title);
      $sql->bindParam(':description', $description);
      $sql->bindParam(':category', $category);
      $sql->bindParam(':budget', $budget, PDO::PARAM_INT);
      $sql->bindParam(':employer', $employer, PDO::PARAM_INT);
      $sql->execute();
      echo 'success';
    } else {
      $sql = $conn->prepare("INSERT INTO projects (employer, title, description, category, budget) VALUES (:employer, :title, :description, :category, :budget)");
      $sql->bindParam(':title', $title);
      $sql->bindParam(':description', $description);
      $sql->bindParam(':category', $category);
      $sql->bindParam(':budget', $budget, PDO::PARAM_INT);
      $sql->bindParam(':employer', $employer, PDO::PARAM_INT);
      $sql->execute();
      echo 'success';
    }
  }
}


function hire($conn, $project, $employer, $freelancer) {
  $sql = $conn->prepare("INSERT INTO completed_projects (project, freelancer, budget) VALUES (:project, :freelancer, :budget)");
  $sql->bindParam(':project', $project);
  $sql->bindParam(':freelancer', $freelancer);
  $sql->bindValue(':budget', 0, PDO::PARAM_INT);
  $sql->execute();
  $sql1 = $conn->prepare("UPDATE projects SET status=:status WHERE id=:id");
  $sql1->bindParam(':id', $project);
  $sql1->bindValue(':status', 'In progress');
  $sql1->execute();
  $sql2 = $conn->prepare("UPDATE bids SET status=:status WHERE freelancer=:freelancer AND project=:project");
  $sql2->bindParam(':project', $project);
  $sql2->bindParam(':freelancer', $freelancer);
  $sql2->bindValue(':status', 'Won');
  $sql2->execute();
  echo 'success';
}


function completeProject($conn, $project, $employer, $budget, $rating, $review) {
  $sql2 = $conn->prepare("UPDATE projects SET status='Completed' WHERE id=:project");
  $sql2->bindParam(':project', $project);
  $sql2->execute();
  $sql = $conn->prepare("UPDATE completed_projects SET budget=:budget, employer_rating=:er, employer_review=:ere, status='Paid' WHERE project=:project");
  $sql->bindParam(':budget', $budget);
  $sql->bindParam(':er', $rating);
  $sql->bindParam(':ere', $review);
  $sql->bindParam(':project', $project);
  $sql->execute();
  $f = $conn->prepare("SELECT * FROM completed_projects WHERE project=:project");
  $f->bindParam(':project', $project);
  $f->execute();
  foreach ($f->fetchAll(PDO::FETCH_ASSOC) as $cp) {
    $bal = getBalance($conn, $cp['freelancer'])+$cp['budget'];
    $sql1 = $conn->prepare("UPDATE account SET balance=:bal WHERE id=:id");
    $sql1->bindParam(':id', $cp['freelancer']);
    $sql1->bindParam(':bal', $bal);
    $sql1->execute();
    $sql3 = $conn->prepare("INSERT INTO notifications (recipient, notification) VALUES (:recipient, :notification)");
    $sql3->bindParam(':recipient', $cp['freelancer']);
    $sql3->bindValue(':notification', 'You have successfully completed working on project <a href="/project?p='.$project.'">'.$project.'. Payment has been made to your account. Keep on working.');
    $sql3->execute();
    $sql4 = $conn->prepare("INSERT INTO notifications (recipient, notification) VALUES (:recipient, :notification)");
    $sql4->bindParam(':recipient', $employer);
    $sql4->bindValue(':notification', 'You have marked project <a href="/project?p='.$project.'">'.$project.'</a> as Completed. Payment has been made to your freelancer. Keep on posting with us.');
    $sql4->execute();
  }
  echo 'success';
}


if (isset($_POST['action'])) {

  if ($_POST['action'] == 'register') {
    register($conn, $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['phone'], $_POST['password'], $_POST['password2'], $_POST['type']);
  }

  if ($_POST['action'] == 'login') {
    login($conn, $_POST['email'], $_POST['password']);
  }

  if ($_POST['action'] == 'complete_profile') {
    completeProfile($conn, $_SESSION['e_id'], $_POST['skills'], $_POST['experience'], $_POST['about'], $_FILES['dp']);
  }

  if ($_POST['action'] == 'bid') {
    postBid($conn, $_SESSION['e_id'], $_POST['budget'], $_POST['bid'], $_POST['project']);
  }

  if ($_POST['action'] == 'postproject') {
    if (isset($_POST['draft'])) {
      $draft = 'true';
    } else {
      $draft = 'false';
    }
    postProject($conn, $_SESSION['e_id'], $_POST['title'], $_POST['budget'], $_POST['category'], $_POST['description'], $draft);
  }

  if ($_POST['action'] == 'deposit') {
    deposit($conn, $_SESSION['e_id'], $_POST['mpesacode']);
  }

  if ($_POST['action'] == 'withdraw') {
    withdraw($conn, $_SESSION['e_id'], $_POST['amount']);
  }

  if ($_POST['action'] == 'buybids') {
    buyBids($conn, $_SESSION['e_id'], $_POST['amount']);
  }

  if ($_POST['action'] == 'hire') {
    hire($conn, $_POST['project'], $_SESSION['e_id'], $_POST['freelancer']);
  }

  if ($_POST['action'] == 'completeproject') {
    completeProject($conn, $_POST['project'], $_SESSION['e_id'], $_POST['budget'], $_POST['employer_rating'], $_POST['employer_review']);
  }
}
?>