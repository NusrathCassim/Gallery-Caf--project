<?php
session_start(); // Start the session to use session variables
include('../../connection/connection.php'); // Adjust this path if necessary


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';


$success = false;

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $id = $_POST['id'];
    $action = $_POST['action'];
    $table_number = $_POST['table_number'];

    if ($action == 'confirm') {
        $status = 'confirmed';
    } else {
        $status = 'rejected';
        $table_number = NULL;
    }

    // Update the reservation status in the database
    $stmt = $conn->prepare("UPDATE reservations SET status = ?, table_number = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("ssi", $status, $table_number, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        if($status == 'confirmed'){
            $table_stmt = $conn->prepare("UPDATE res_table SET status ='booked' WHERE table_no = ? ");
            $table_stmt->bind_param("s", $table_number);
            $table_stmt->execute();
            $table_stmt->close();

        }
        // Fetch the user email and name
        $email_stmt = $conn->prepare("SELECT email, name FROM reservations WHERE id = ?");
        $email_stmt->bind_param("i", $id);
        $email_stmt->execute();
        $email_result = $email_stmt->get_result();
        $user = $email_result->fetch_assoc();
        $user_email = $user['email'];
        $user_name = $user['name'];

        // Send the email
        $mail = new PHPMailer(true);
        try {
            // Server settings
            // add the codes to set up the php mailer
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        $success = true;
        $_SESSION['alert'] = "Reservation Updated successfully!";
        header("Location: bookings.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/main-folder/admin/template-admin.css">
    <link rel="stylesheet" href="../../general/home.css">
    <link rel="stylesheet" href="bookings.css">
    <script src="../../general/common.js" defer></script>
    <title>Admin</title>
</head>
<body>
    <?php include('../template_admin.php'); ?>
    
    <section class="home-section">
        <div class="home-content">
            <i class="bx bx-menu"></i>
            <span class="text">Manage Reservation</span>
        </div>
        <div class="next">
            <div class="reservations-table">
            <?php
            // Fetch pending reservations
            $sql = "SELECT * FROM reservations WHERE status = 'pending'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Name</th><th>Contact Info</th><th>Number of Guests</th><th>Date</th><th>Time</th><th>Actions</th></tr>";
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['contact_info'] . "</td>";
                    echo "<td>" . $row['num_guests'] . "</td>";
                    echo "<td>" . $row['reservation_date'] . "</td>";
                    echo "<td>" . $row['reservation_time'] . "</td>";
                    echo "<td>
                            <form action='bookings.php' method='post'>
                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                <select name='table_number'>
                                    <option value=''>Select Table</option>";
                                    $table_sql = "SELECT table_no, capacity FROM res_table where status = 'free'";
                                    $table_result = $conn->query($table_sql);
                                    if ($table_result->num_rows > 0) {
                                        while($table_row = $table_result->fetch_assoc()) {
                                            echo "<option value='" . $table_row['table_no'] . "'>Table " . $table_row['table_no'] ." capacity " . $table_row['capacity'] . "</option>";
                                        }
                                    }
                    echo "        </select>
                                <button type='submit' name='action' value='confirm'>Confirm</button>
                                <button type='submit' name='action' value='reject'>Reject</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No pending reservations.";
            }

            $conn->close();
            ?>
            </div>
            
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
