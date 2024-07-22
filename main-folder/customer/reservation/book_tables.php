<?php
session_start(); // Start the session to use session variables
$success = false;
include('../../connection/connection.php'); // Adjust this path if necessary

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['user_id'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact_info = $_POST['contact_info'];
    $num_guests = $_POST['num_guests'];
    $reservation_date = $_POST['reservation_date'];
    $reservation_time = $_POST['reservation_time'];
    $user_id = $_SESSION['user_id']; // Get the user ID from the session

    $stmt = $conn->prepare("INSERT INTO reservations (user_id, name, contact_info, num_guests, reservation_date, reservation_time, email ) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ississs", $user_id, $name, $contact_info, $num_guests, $reservation_date, $reservation_time, $email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $success = true;
        $_SESSION['alert'] = "Reservation added successfully!";
        header("Location: book_tables.php");
        exit();
    } else {
        echo "Error adding reservation: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "User ID not found in session.";
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
    <link rel="stylesheet" href="reservation.css"> <!-- Ensure your menu-specific CSS is linked -->
    <!-- Include the JavaScript file -->
    <script src="../../general/common.js" defer></script>
    <title>Reservation</title>
</head>
<body>
    <?php include('../../general/template.php'); ?>
    
    
    <section class="home-section">
        <div class="home-content">
            <i class="bx bx-menu"></i>
            <span class="text">Book Now</span>
        </div>
       
        <div class="next">
            <div class="form_data">
                <form action="book_tables.php" method="post" enctype="multipart/form-data" class="form-book">
                   

                <div class="form-row">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required><br>
                </div>
                <div class="form-row">
                    <label for="contact_info">Contact Info:</label>
                    <input type="text" id="contact_info" name="contact_info" required><br>
                </div>
                <div class="form-row">
                    <label for="email">Your Email:</label>
                    <input type="email" id="email" name="email" required><br>
                </div>

                <div class="form-row">
                    <label for="num_guests">No of Guests:</label>
                    <input type="number" id="num_guests" name="num_guests" required><br>
                </div>
                <div class="form-row">
                    <label for="reservation_date">Date:</label>
                    <input type="date" id="reservation_date" name="reservation_date" required><br>
                </div>
                <div class="form-row">
                    <label for="reservation_time">Time:</label>
                    <input type="time" id="reservation_time" name="reservation_time" required><br>
                </div>
                    <div class="btn_box">
                    <input type="submit" value="Book Now">
                    <input type="reset" class="btn1" name="cancel" value="Cancel"> 

                    </div>
                   
                </form>
            </div>
            <div class="message">
                <marquee>Reservation Details will be sent to your email</marquee>
            </div>
            <!-- <div class="display_submit_data">
                
                <?php
                //fetch accepted reservations relevant to the user
                $user_id = $_SESSION['user_id']; // Get the user ID from the session
                $stmt = $conn->prepare("SELECT * FROM reservations WHERE user_id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result->num_rows > 0){
                    echo "<div class='cards'>";
                    while($row = $result->fetch_assoc()){
                        echo "<div class='card'>";
                        echo "<p>Table Number: " . $row['table_number'] . "</p>";
                        echo "<p>Number of Guests: " . $row['num_guests'] . "</p>";
                        echo "<p>Date: " . $row['reservation_date'] . "</p>";
                        echo "<p>Time: " . $row['reservation_time'] . "</p>";
                        // Translate the status value to a user-friendly text
                        $status_text = '';
                        switch ($row['status']) {
                            case 'pending':
                                $status_text = 'Reservation Pending';
                                break;
                            case 'confirmed':
                                $status_text = 'Reservation Confirmed';
                                break;
                            case 'rejected':
                                $status_text = 'Reservation Rejected';
                                break;
                        }
                        echo "<p>Status: " . $status_text . "</p>";
                        echo "</div>"; // close the card div
                    }
                    echo "</div>"; // close the cards div
                }else{
                    echo "No reservations found";
                }
                ?>
             </div>
           -->

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