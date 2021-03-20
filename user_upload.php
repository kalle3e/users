<?php
include 'libraries.php';
$fileoptions = new FileOptions();
$fileoptions->init();
if ($fileoptions->ishelp) {
    help();
    exit;
}
$conn = connectDB($fileoptions);
$usersArray = readCSV($fileoptions->fileName);
$db = new Db($fileoptions,$usersArray,$conn);
if ($fileoptions->iscreate){
    $db->createTable();
    echo "/n Table Created/n";
    $conn = null;
    exit;
}
$db->insert();
$conn = null;
?>