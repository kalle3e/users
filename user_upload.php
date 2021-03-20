<?php
include 'libraries.php';
$fileoptions = new FileOptions();
$fileoptions->init();
if ($fileoptions->ishelp){
    help();
    exit;
}
$conn = connectDB($fileoptions);
if (!$fileoptions->iscreate){
    $usersArray = readCSV($fileoptions->fileName);
}
$db = new Db($fileoptions,$usersArray,$conn);
if ($fileoptions->iscreate){
    $db->createTable();
    $conn = null;
    exit;
}
$db->insert();
$conn = null;
?>