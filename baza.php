<?php
$host = 'localhost';
$dbname = 'trgovina';
$username = 'root';
$password = '';
$conn = mysqli_connect($host,$username,$password,$dbname) or die("pocezovanje ni mogoce");

mysqli_set_charset($conn,"utf8");
?>