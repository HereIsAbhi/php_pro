<?php
session_start(); // Start the session
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
header("Location: login_signup.php"); // Redirect to login page
exit();
?>