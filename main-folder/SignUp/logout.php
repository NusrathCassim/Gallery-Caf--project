<?php
session_start();
//make that session to a empty array
$_SERVER = array();
// Destroy the session
session_destroy();

// Redirect to login page
header('Location: /main-folder/admin/admin-log.php');
exit();
?>