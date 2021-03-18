<?php
include 'libraries.php';
include 'connect.php';

$userarray = readCSV();
insert($userarray, $conn);
$conn = null;
?>