<?php

// Connecting to the MySQL database
$username = 'leer1';
$password = 'ne7tEchA';

$database = new PDO('mysql:host=localhost;dbname=db_spring18_leer1', $username, $password);
function my_autoLoader($class) {
    include 'classes/class.' . $class . ".php";
}

spl_autoload_register('my_autoLoader');

// Start the session
session_start();

$current_url = basename($_SERVER['REQUEST_URI']);

// if customerID is not set in the session and current URL not login.php redirect to login page
if (!isset($_SESSION["customerID"]) && $current_url != 'login.php') {
    header("Location: login.php");
}

// Else if session key customerID is set get $customer from the database
elseif (isset($_SESSION["customerID"])) {
    //Take this block of code into the classes page
	$customer01 = new Customer($_SESSION['customerID'], $database);
}

?>