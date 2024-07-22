<?php
session_start();

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Add the reservation ID to the hidden reservations array in the session
    if (!in_array($id, $_SESSION['hidden_reservations'])) {
        $_SESSION['hidden_reservations'][] = $id;
    }
}
?>
