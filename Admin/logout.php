<?php
session_start();
session_destroy();

// Clear any existing cookies
setcookie('email', '', time() - 3600, "/", "", true, true);
setcookie('password', '', time() - 3600, "/", "", true, true);

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

header("Location: login.php");
exit();
?>
