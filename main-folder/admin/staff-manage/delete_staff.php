<?php
session_start();
include('../../connection/connection.php'); // Adjust the path as necessary

if (isset($_GET['id'])) {
    $id = (int) $_GET['id']; // Validate the id parameter

    try {
        // Delete the staff member from the database
        $query = "DELETE FROM staff WHERE id =?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Redirect to the staff list page with a success message
        header('Location: /main-folder/admin/staff-manage/add-staff.php');
        exit;
    } catch (Exception $e) {
        // Display an error message
        echo 'Error: ' . $e->getMessage();
    }
}
?>