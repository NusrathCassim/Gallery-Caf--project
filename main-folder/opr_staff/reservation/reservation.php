<?php
session_start();
include('../../connection/connection.php');
$success = false; // Initialize the success variable

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/main-folder/admin/template-admin.css">
    <link rel="stylesheet" href="../../general/home.css">
    <link rel="stylesheet" href="/main-folder/admin/Reservation/bookings.css">
    <link rel="stylesheet" href="/main-folder/admin/admin-home.css">
    
   
    <script src="../../general/common.js" defer></script>
    <title>staff</title>
</head>
<body>
    <?php include('../opr_template.php');?>
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
                            <form action='confirm.php' method='post'>
                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                <select name='table_number'>
                                    <option value=''>Select Table</option>";
                                    $table_sql = "SELECT table_no FROM res_table where status = 'free'";
                                    $table_result = $conn->query($table_sql);
                                    if ($table_result->num_rows > 0) {
                                        while($table_row = $table_result->fetch_assoc()) {
                                            echo "<option value='" . $table_row['table_no'] . "'>Table " . $table_row['table_no'] . "</option>";
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

           
            ?>
            </div>
            <div class="other">
            <div class="table_info">
                    <?php
                    //table for all reservation details
                    $stmt_r = $conn->prepare("SELECT * FROM reservations");
                    $stmt_r->execute();
                    $result_r = $stmt_r->get_result();
                    if($result_r->num_rows >0){
                        echo "<table>";
                        echo "<tr>";
                        echo "<th>Table No</th>";
                        echo "<th>No of Guests</th>";
                        echo "<th>Date</th>";
                        echo "<th>Time</th>";
                        echo "<th>Status</th>";
                        echo "</tr>";
                        while($row = $result_r->fetch_assoc()){
                            echo "<tr>";
                            echo "<td>" . $row['table_number'] . "</td>";
                            echo "<td>" . $row['num_guests'] . "</td>";
                            echo "<td>" . $row['reservation_date'] . "</td>";
                            echo "<td>" . $row['reservation_time'] . "</td>";
                            // Translate the status value to a user-friendly text
                            $status_text = '';
                            switch ($row['status']) {
                                case 'pending':
                                    $status_text = 'Pending';
                                    break;
                                case 'confirmed':
                                    $status_text = 'Confirmed';
                                    break;
                                case 'rejected':
                                    $status_text = 'Rejected';
                                    break;
                            }
                            echo "<td>" . $status_text . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    }else{
                        echo "No reservations found";
                    }
                    $conn->close();
                    ?>
                </div>
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