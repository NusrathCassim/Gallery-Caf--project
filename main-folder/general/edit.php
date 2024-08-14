<?php
session_start();
include('../connection/connection.php');

// Initialize variables
$success = false;
$error = '';

// Get the user ID from the session
$user_id = $_SESSION['user_id']; // Ensure 'user_id' is set in the session when the user logs in

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);

    if (empty($name) || empty($address) || empty($phone) || empty($dob)) {
        $error = 'All required fields are required!';
    } else {
        // Update existing details
        $sql = "UPDATE user_details SET name = ?, address = ?, phone = ?, dob = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $address, $phone, $dob, $user_id);

        if ($stmt->execute()) {
            $success = true;
        } else {
            $error = 'Failed to update data: ' . $stmt->error;
        }
        $stmt->close();
    }
}

// Redirect to the display page after updating
if ($success) {
    header('Location: display_user.php');
    exit();
}
?>
