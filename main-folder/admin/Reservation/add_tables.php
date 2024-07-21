<?php
session_start();
include('../../connection/connection.php');
$success = false; // Initialize the success variable

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $table_no = $_POST['table_no'];
    $capacity = $_POST['capacity'];

    $stmt = $conn->prepare("INSERT INTO `res_table` ( `table_no`, `capacity`) VALUES (?, ?)");
    $stmt->bind_param("ss", $table_no, $capacity);
    $stmt->execute();

    if($stmt->affected_rows > 0){
        $_SESSION['alert'] = 'Table added successfully!';
        header("Location: add_tables.php"); // Redirect to avoid form resubmission
        exit();
    } else {
        $_SESSION['alert'] = 'Error adding table!';
        header("Location: add_tables.php"); // Redirect to display the error message
        exit();
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
    <link rel="stylesheet" href="reservation.css">
    <script src="../../general/common.js" defer></script>
    <title>Admin</title>
</head>
<body>
    <?php include('../template_admin.php'); ?>
    
    <section class="home-section">
        <div class="home-content">
            <i class="bx bx-menu"></i>
            <span class="text">Add Tables</span>
        </div>
        <div class="next">
            <div class="table_container">
                <form action="/main-folder/admin/Reservation/add_tables.php" method="post" enctype="multipart/form-data" class="form_table">
                    
                    <label for="table_no">Table No:</label>
                    <input type="text" id="table_no" name="table_no" required><br><br>
                    <label for="capacity">Capacity:</label>
                    <input type="text" id="capacity" name="capacity" required><br><br>
                    <input type="submit" value="Add">
                    <input type="reset" value="Reset">
                    

                </form>

            </div>
            
            <div class="display_tables">
                
                    <div class="cards-container">
                        <div class="cards">
                            <?php
                            $sql = "SELECT * FROM `res_table`";
                            $result = mysqli_query($conn, $sql);
                            if(mysqli_num_rows($result)>0 ){
                                while($fetch = mysqli_fetch_assoc($result)){
                                    echo '<div class="card">';
                                    echo '<div class="card-content">';
                                    echo '<h3> No: ' . htmlspecialchars($fetch['table_no']) . '</h3>';
                                    echo '<p> Capacity: ' . htmlspecialchars($fetch['capacity']) . '</p>';
                                    echo '</div>';
                                    echo '<input type="delete" class="btn" value="Delete" onclick="location.href=\'t_delete.php?id=' . $fetch['id'] . '\'">';
                                    echo '</div>';
                                }
                            }else{
                                echo '<p>No tables found</p>';
                            }
                            ?>
                        </div>
                    </div>
                   
                
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
