<?php
session_start(); // Start the session to use session variables
include('../../connection/connection.php'); // Adjust this path if necessary


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

$success = false; // Initialize the success variable
function sendEmail($email, $subject, $message) {
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();                                 // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                          // Enable SMTP authentication
        $mail->Username = 'cassimnusrat@gmail.com';      // SMTP username
        $mail->Password = 'gqtogztwyzmpgyho';               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;                    // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                               // TCP port to connect to

        //Recipients
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
    $id = $_POST['id'];
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
    header("Location: pre-order.php"); // Redirect back to the admin page
    exit();
}
?>