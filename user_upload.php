<?php
include 'libraries.php';
//include 'connectDB.php';
$fileoptions = new FileOptions();
$fileoptions->init();
// new Db
//   ** Connect DB
$conn = connectDB($fileoptions);
$userArray = readCSV($fileoptions->fileName);
$db = new Db($fileoptions,$conn);
if ($fileoptions->iscreate){
    // use database;
    $db->createTable();
    //create table
    echo "/n Table Created/n";
}
insert($userArray, $conn);
$conn = null;
?>