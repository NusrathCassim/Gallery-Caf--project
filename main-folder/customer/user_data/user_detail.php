<?php
session_start();
include('../../connection/connection.php');

// Initialize variables
$success = false;
$error = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);

    // Get the user ID from the session
    $user_id = $_SESSION['user_id']; // Ensure 'user_id' is set in the session when the user logs in

    if (empty($name) || empty($address) || empty($phone) || empty($dob) || empty($user_id)) {
        $error = 'All required fields are required!';
    } else {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO user_details (user_id, name, address, phone, dob) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $user_id, $name, $address, $phone, $dob);

        if ($stmt->execute()) {
            $success = true;
        } else {
            $error = 'Failed to insert data: ' . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../general/template.css">
    <link rel="stylesheet" href="../../general/home.css">
    <link rel="stylesheet" href="user_detail.css">
    <script src="../../general/common.js" defer></script>
    <title>User Details</title>
</head>
<body>
    <?php include('../../general/template.php'); ?>

    <section class="home-section">
        <div class="home-content">
            <i class="bx bx-menu"></i>
            <span class="text">User Details</span>
        </div>

        <div class="next">
            <div class="form">
            <div class="form-content">

            
            <?php if ($success): ?>
                <p>Details submitted successfully!</p>
            <?php elseif ($error): ?>
                <p>Error: <?php echo htmlspecialchars($error); ?></p>
            <?php else: ?>
                <form action="" method="post">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" placeholder="Name" required>

                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" placeholder="Address" required>

                    <label for="phone">Phone Number</label>
                    <input type="tel" name="phone" id="phone" placeholder="Phone Number" required>

                    <label for="dob">Date of Birth</label>
                    <input type="date" name="dob" id="dob" required>

                    <button type="submit">Submit</button>
                </form>
            <?php endif; ?>
            <a href="javascript:history.back()">Go Back</a>
            </div>
            
        </div>
    </section>
</body>
</html>
