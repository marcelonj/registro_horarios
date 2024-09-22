<?php
require('./vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable('../');
$dotenv->load();

$server= $_ENV["SERVER"];
$user= $_ENV["USER"];
$pass= $_ENV["PASS"];
$database= $_ENV["DATABASE"];

$conn= mysqli_connect($server, $user, $pass, $database);
?>