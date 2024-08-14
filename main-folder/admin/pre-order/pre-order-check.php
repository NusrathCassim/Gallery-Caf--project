<?php
session_start(); 
include('../../connection/connection.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

$success = false; // Initialize the success variable

function sendEmail($email, $subject, $message) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();                                 // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                          // Enable SMTP authentication
        $mail->Username = 'cassimnusrat@gmail.com';      // SMTP username
        $mail->Password = 'gqtogztwyzmpgyho';               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;                    // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                               // TCP port to connect to

        // Recipients
        $mail->setFrom('cassimnusrat@gmail.com', 'Gallery Cafe'); // Replace with your email and name
        $mail->addAddress($email);                       // Add a recipient

        // Content
        $mail->isHTML(true);                            // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['order_id'];  // Ensure correct field name
    $user_id = $_POST['user_id'];
    $action = $_POST['action'];

    // Fetch user's email based on user_id
    $stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($email);
    $stmt->fetch();
    $stmt->close();

    if ($action == 'confirm') {
        $stmt = $conn->prepare("UPDATE pre_order SET status = 'confirmed' WHERE id = ?");
        $stmt->bind_param("i", $id);
        if($stmt->execute()) {
            // Send confirmation email
            $subject = "Your pre-order has been confirmed!";
            $message = "Dear Customer, your pre-order with ID $id has been confirmed. Thank you!";
            if (sendEmail($email, $subject, $message)) {
                $_SESSION['alert'] = "Pre-order confirmed and email sent successfully.";
            } else {
                $_SESSION['alert'] = "Pre-order confirmed, but failed to send email.";
            }
        } else {
            $_SESSION['alert'] = "Failed to confirm pre-order.";
        }
        $stmt->close();
    } elseif ($action == 'reject') {
        $stmt = $conn->prepare("UPDATE pre_order SET status = 'rejected' WHERE id = ?");
        $stmt->bind_param("i", $id);
        if($stmt->execute()) {
            // Send rejection email
            $subject = "Your pre-order has been rejected.";
            $message = "Dear Customer, unfortunately, your pre-order with ID $id has been rejected. Please contact us for more details.";
            if (sendEmail($email, $subject, $message)) {
                $_SESSION['alert'] = "Pre-order rejected and email sent successfully.";
            } else {
                $_SESSION['alert'] = "Pre-order rejected, but failed to send email.";
            }
        } else {
            $_SESSION['alert'] = "Failed to reject pre-order.";
        }
        $stmt->close();
    }
    $conn->close();
    header("Location: pre-order-check.php"); // Redirect back to the admin page
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/main-folder/admin/template-admin.css">
    <link rel="stylesheet" href="../../general/home.css">
    <link rel="stylesheet" href="/main-folder/admin/add-menu/add-menu.css">
    <link rel="stylesheet" href="pre-order-check.css">
    <script src="../../general/common.js" defer></script>
    <title>Admin</title>
</head>
<body>
    <?php include('../template_admin.php'); ?>
    
    <section class="home-section">
        <div class="home-content">
            <i class="bx bx-menu"></i>
            <span class="text">Pre-Order</span>
        </div>
        <div class="next">
            <div class="card_set2">
                <!-- Card Counts for Pre-Orders -->
                <div class="card2">
                    <div class="header">
                        <i class='bx bxs-check-circle'></i>
                        <h2>Confirmed</h2>
                        <?php
                            $stmt = $conn->prepare("SELECT COUNT(*) FROM pre_order WHERE status = 'confirmed'");
                            $stmt->execute();
                            $stmt->bind_result($count);
                            $stmt->fetch();
                            echo "<span class='counter'>" . $count . "</span>";
                            $stmt->close();
                        ?>
                    </div>
                </div>
                <div class="card2">
                    <div class="header">
                        <i class='bx bxs-time-five'></i>
                        <h2>Pending</h2>
                        <?php
                            $stmt = $conn->prepare("SELECT COUNT(*) FROM pre_order WHERE status = 'pending'");
                            $stmt->execute();
                            $stmt->bind_result($count);
                            $stmt->fetch();
                            echo "<span class='counter'>" . $count . "</span>";
                            $stmt->close();
                        ?>
                    </div>
                </div>
                <div class="card2">
                    <div class="header">
                        <i class='bx bxs-x-circle'></i>
                        <h2>Rejected</h2>
                        <?php
                            $stmt = $conn->prepare("SELECT COUNT(*) FROM pre_order WHERE status = 'rejected'");
                            $stmt->execute();
                            $stmt->bind_result($count);
                            $stmt->fetch();
                            echo "<span class='counter'>" . $count . "</span>";
                            $stmt->close();
                        ?>
                    </div>
                </div>
            </div>

            <div class="pre-order">
                <?php
                // SQL query to fetch pending pre-orders with item names concatenated
                $sql = "SELECT p.id AS order_id, p.user_id, p.visiting_date, 
                            GROUP_CONCAT(CONCAT(m.Item, ' (Qty: ', o.quantity, ')') SEPARATOR '<br>') AS items,
                            SUM(o.price * o.quantity) AS total_price
                        FROM pre_order p
                        INNER JOIN order_items o ON p.id = o.order_id
                        INNER JOIN menu m ON m.id = o.menu_item_id
                        WHERE p.status = 'pending'
                        GROUP BY p.id, p.user_id, p.visiting_date";

                $result = $conn->query($sql);

                // Check if there are results
                if ($result->num_rows > 0) {
                    echo "<table border='1'>";
                    echo "<tr>
                            <th>User ID</th>
                            <th>Items & Quantity</th>
                            <th>Total Price</th>
                            <th>Visiting Date</th>
                            <th>Action</th>
                        </tr>";

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['user_id'] . "</td>";
                        echo "<td>" . $row['items'] . "</td>";
                        echo "<td>" . $row['total_price'] . "</td>";
                        echo "<td>" . $row['visiting_date'] . "</td>";
                        echo "<td>
                                <form action='pre-order-check.php' method='post'>
                                    <input type='hidden' name='order_id' value='" . $row['order_id'] . "'>
                                    <input type='hidden' name='user_id' value='" . $row['user_id'] . "'>
                                    <input type='hidden' name='visiting_date' value='" . $row['visiting_date'] . "'>
                                    <button type='submit' name='action' value='confirm'>Confirm</button>
                                    <button type='submit' name='action' value='reject'>Reject</button>    
                                </form>
                            </td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No pending pre-orders.";
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
