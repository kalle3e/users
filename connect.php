<?php
$h = "localhost";
$username = "userupload";
$passw = "userupload";
$dbname = "userupload";

$servername = $h;
$user = $username;
$pass = $passw;
$db = $dbname;
try {
    $conn = new PDO("mysql:host=$servername;dbname=$db", $user, $pass);
    echo "Connected successfully";
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
}
?>

