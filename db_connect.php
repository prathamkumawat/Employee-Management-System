<!-- db_connect.php -->

<?php
$host = 'localhost';
$db = 'emp_mgmt';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error){
        die("Connection failed: ".$conn->connect_error);
}
?>
