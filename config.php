<?php
session_start();
$host = "localhost";
$user = "root";
$pass = '';
$db = "travelq";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("can't connect database");
}