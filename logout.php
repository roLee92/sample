<?php

// Create and include a configuration file with the database connection
include('config.php');

// Include functions for application
include('functions.php');

// Destroy the current session
session_destroy();

// Redirect to the login page
header('location: login.php');