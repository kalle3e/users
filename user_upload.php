<?php
include 'libraries.php';

$fileoptions = new FileOptions();
$fileoptions->init();
//   ** Connect DB
$conn = connectDB($fileoptions);
$userArray = readCSV($fileoptions->fileName);
if ($fileoptions->iscreate){
    //create table
    insert($userArray, $conn);
    echo "/n Table Created/n";
}
$conn = null;
?>