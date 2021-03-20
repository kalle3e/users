<?php
include 'libraries.php';
$fileoptions = new FileOptions();
$fileoptions->init();
$conn = connectDB($fileoptions);
$usersArray = readCSV($fileoptions->fileName);
$db = new Db($fileoptions,$usersArray,$conn);
if ($fileoptions->iscreate){
    $db->createTable();
    $conn = null;
    exit;
}
$db->insert();
$conn = null;
?>