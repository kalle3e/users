<?php
include 'libraries.php';

$fileoptions = new FileOptions();
$fileoptions->init();
//   ** Connect DB
$conn = connectDB($fileoptions);
$userArray = readCSV($fileoptions->fileName);
if ($fileoptions->iscreate){
    createDatabase();
    createTable();
    //create table
    echo "/n Table Created/n";
}
insert($userArray, $conn);
$conn = null;
?>