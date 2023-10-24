<?php
  error_reporting( E_ALL );
  ini_set( "display_errors", 1 );
?>

<?php
$hostName = "incheon-db.c7fk9rroi8eq.ap-northeast-2.rds.amazonaws.com";
$userName = "wise";
$password = "wisenincheon";
$databaseName = "incheon-db";
$conn = new mysqli($hostName, $userName, $password, $databaseName);
// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
};
?>
