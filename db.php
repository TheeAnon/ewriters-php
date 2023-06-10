<?php
try {
  $host = "localhost";
  $dbname = "ewriters_ewriters";
  $username = "ewriters_anon";
  $password = "sfFKcjvsL9RcghJ";

  $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  //var_dump($conn);
}catch(Exception $e) {
  echo 'Something went wrong. Contact developer.';
}
?>