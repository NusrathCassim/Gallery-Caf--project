<?php
session_start(); // Start the session to use session variables
include('../../connection/connection.php'); // Adjust this path if necessary

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // SQL query to delete the menu item
    $sql = "DELETE FROM `menu` WHERE `id` = '$id'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $_SESSION['alert'] = 'Menu item deleted successfully!';
    } else {
        $_SESSION['alert'] = 'Error deleting menu item!';
    }
} else {
    $_SESSION['alert'] = 'Invalid menu item!';
}

// Redirect back to the previous page
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>
