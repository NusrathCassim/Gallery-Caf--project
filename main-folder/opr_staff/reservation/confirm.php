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
            //Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
            $mail->SMTPAuth   = true;
            $mail->Username   = 'cassimnusrat@gmail.com'; // SMTP username
            $mail->Password   = 'gqtogztwyzmpgyho'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            //Recipients
            $mail->setFrom('cassimnusrat@gmail.com', 'Gallery Cafe');
            $mail->addAddress($user_email, $user_name);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Reservation ' . ucfirst($status);
            $mailContent = "<p>Dear $user_name,</p>";
            $mailContent .= "<p>Your reservation has been <strong>$status</strong>.</p>";
            if ($status == 'confirmed') {
                $mailContent .= "<p>Your table number is <strong>$table_number</strong>.</p>";
            }
            $mailContent .= "<p>Thank you for choosing our restaurant.</p>";
            $mailContent .= "<p>Best Regards,<br>Gallery cafe</p>";

            $mail->Body = $mailContent;

            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        $success = true;
        $_SESSION['alert'] = "Reservation Updated successfully!";
        header("Location: /main-folder/opr_staff/reservation/reservation.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>