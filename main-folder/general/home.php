<?php
session_start();
include('../connection/connection.php');

// Get the user ID from the session
$user_id = $_SESSION['user_id']; // Ensure 'user_id' is set in the session when the user logs in

// Fetch user details
$sql = "SELECT name, address, phone, dob FROM user_details WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- avoid the user from going back  -->
    <script type="text/javascript">
        function preventBack() {
            window.history.forward();
        };
        setTimeout("preventBack()", 0);
        window.onunload = function() {null;}
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link rel="stylesheet" href="template.css">
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="display_user.css">
    <!-- pop up edit view -->
    <script>
        function openEditForm() {
            document.getElementById('edit-form').style.display = 'block';
        }
        function closeEditForm() {
            document.getElementById('edit-form').style.display = 'none';
        }
    </script>
</head>
<body>
<?php include('template.php'); ?>

<section class="home-section">
    <div class="home-content">
        <i class="bx bx-menu"></i>
        <span class="text">Welcome <?php echo $_SESSION["username"]; ?></span>
    </div>
    <div class="next">
        <div class="user-content">
            <h2>User Details</h2>
            <?php if ($user): ?>
                <div class="user-card">
                    <div class="user-field">
                        <p class="field-label">Name:</p>
                        <p class="field-value"><?php echo htmlspecialchars($user['name']) ?? 'Not provided'; ?></p>
                    </div>
                    <div class="user-field">
                        <p class="field-label">Address:</p>
                        <p class="field-value"><?php echo htmlspecialchars($user['address']) ?? 'Not provided'; ?></p>
                    </div>
                    <div class="user-field">
                        <p class="field-label">Phone Number:</p>
                        <p class="field-value"><?php echo htmlspecialchars($user['phone']) ?? 'Not provided'; ?></p>
                    </div>
                    <div class="user-field">
                        <p class="field-label">Date of Birth:</p>
                        <p class="field-value"><?php echo htmlspecialchars($user['dob']) ?? 'Not provided'; ?></p>
                    </div>
                </div>
                <button class="edit-btn" onclick="openEditForm()">Edit Details</button>
            <?php else: ?>
                <p>No user details found. Please update your information.</p>
                <button class="edit-btn" onclick="openEditForm()">Update Information</button>
            <?php endif; ?>
        </div>

        <!-- Popup Edit Form -->
        <div id="edit-form" class="edit-form">
            <span class="close" onclick="closeEditForm()">&times;</span>
            <h2>Edit User Details</h2>
            <form action="edit_user.php" method="post">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['name']) ?? ''; ?>" required>

                <label for="address">Address</label>
                <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($user['address']) ?? ''; ?>" required>

                <label for="phone">Phone Number</label>
                <input type="tel" name="phone" id="phone" value="<?php echo htmlspecialchars($user['phone']) ?? ''; ?>" required>

                <label for="dob">Date of Birth</label>
                <input type="date" name="dob" id="dob" value="<?php echo htmlspecialchars($user['dob']) ?? ''; ?>" required>

                <button type="submit">Update</button>
                <button type="button" onclick="closeEditForm()">Cancel</button>
            </form>
        </div>
    </div>
</section>

</body>
<script src="common.js"></script>
</html>
