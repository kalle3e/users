<?php
function connectDB($login)
{
    $servername = $login->host;
    $user = $login->name;
    $pass = $login->password;
    $db = "userupload";
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$db", $user, $pass);
        echo "Connected successfully";
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    return $conn;
}
?>

