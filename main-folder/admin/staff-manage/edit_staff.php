<?php
session_start();
include('../../connection/connection.php'); // Adjust the path as necessary

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Retrieve the staff member's details from the database
    $query = "SELECT * FROM staff WHERE id =?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $staff = $result->fetch_assoc();

    if (isset($_POST['update_staff'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Update the staff member's details in the database
        $query = "UPDATE staff SET username =?, password =? WHERE id =?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $username, $password, $id);
        $stmt->execute();

        if($stmt->affected_rows > 0 ){
            echo "<script>alert('Staff Updated successfully!');</script>";
        }else {
            echo "<script>alert('Failed to Update staff!');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/main-folder/admin/template-admin.css">
    <link rel="stylesheet" href="../../general/home.css">
    <link rel="stylesheet" href="add-staff.css">
    
   
    <script src="../../general/common.js" defer></script>
    <title>Admin</title>
</head>
<body>
    <?php include('../template_admin.php');?>
    <section class="home-section">
        <div class="home-content">
            <i class="bx bx-menu"></i>
            <span class="text">Add Staff</span>
        </div>
        <div class="next">
            <form action="" method="post" class="form-staff">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $staff['username']; ?>" required><br><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br><br>
                <input type="submit" name="update_staff" value="Update Staff">
            </form>
        </div>
    </section>
    <script>
        // Check if the session variable 'alert' is set and display the alert
        <?php if (isset($_SESSION['alert'])): ?>
            alert("<?php echo $_SESSION['alert']; ?>");
            <?php unset($_SESSION['alert']); ?> // Clear the session variable after displaying the alert
        <?php endif; ?>
    </script>
</body>
</html>