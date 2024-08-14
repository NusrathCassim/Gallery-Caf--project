<?php
session_start();
include('../../connection/connection.php'); // Adjust the path as necessary

if (isset($_POST['add_staff'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password using password_hash()
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the staff data into the database
    $query = "INSERT INTO staff (username, password) VALUES (?,?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username,  $hashed_password );
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Staff added successfully!');</script>";
    } else {
        echo "<script>alert('Failed to add staff!');</script>";
    }
}

// Retrieve all staff from the database
$query = "SELECT * FROM staff";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$staff_list = array();
while ($row = $result->fetch_assoc()) {
    $staff_list[] = $row;
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
                <input type="text" id="username" name="username" required><br><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br><br>
                <input type="submit" name="add_staff" value="Add Staff">
            </form>

           
            <table>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($staff_list as $staff) {?>
                <tr>
                    <td><?php echo $staff['id'];?></td>
                    <td><?php echo $staff['username'];?></td>
                    <td>
                        <div class="b">

                       
                        <a href="edit_staff.php?id=<?php echo $staff['id'];?>" class="button-link">Edit  </a>
                        <a href="delete_staff.php?id=<?php echo $staff['id'];?>" class="button-link delete">Delete</a>
                    </div>
                    </td>
                </tr>
                <?php }?>
            </table>
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