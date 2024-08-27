<?php
session_start(); // Start the session

// Clear all session variables
$_SESSION = array();

// If you want to clear all session cookies as well
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Clear the "Remember Me" cookies
setcookie('email', '', time() - 3600, "/", "", true, true); // Clear email cookie
setcookie('password', '', time() - 3600, "/", "", true, true); // Clear password cookie

// Redirect to the login page
header("Location: login.php");
exit();
