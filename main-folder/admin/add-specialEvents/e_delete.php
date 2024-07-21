<!-- delete event items -->
<?php
session_start();
include('../../connection/connection.php');

if(isset($_GET['id'])){
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "DELETE FROM `events` WHERE `id` = '$id'";
    $result = mysqli_query($conn, $sql);
    if($result){
        $_SESSION['alert'] = 'Event deleted successfully!';
    } else {
        $_SESSION['alert'] = 'Error deleting event!';
    }
    
}else {
    $_SESSION['alert'] = 'Invalid menu item!';
}

// Redirect back to the previous page
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();


?>