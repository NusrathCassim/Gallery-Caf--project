<?php
include('../../connection/connection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../general/template.css">
    <link rel="stylesheet" href="../../general/home.css">
    <link rel="stylesheet" href="special-events.css"> <!-- Ensure your menu-specific CSS is linked -->
    <!-- Include the JavaScript file -->
    <script src="../../general/common.js" defer></script>
    <title>Special</title>
</head>
<body>
    <?php include('../../general/template.php'); ?>
    
    
    <section class="home-section">
        <div class="home-content">
            <i class="bx bx-menu"></i>
            <span class="text">Special Events</span>
        </div>
       
        <div class="next">
        <div class="cards">
            <!-- //selecting event items -->
                <?php
                    $sql = "SELECT * FROM `events`";
                    $result = mysqli_query($conn, $sql);
                    if(mysqli_num_rows($result)>0 ){
                        while($fetch = mysqli_fetch_assoc($result)){
                            echo '<div class="card">';
                            // Display event item details as cards
                            echo '<div class="card-content">';
                            //display event items
                            echo '<div class="card-header">';
                            echo '<h3>' . htmlspecialchars($fetch['e_name']) . '</h3>';
                            echo '</div>';
                            echo '<div class = "description">';
                            echo '<p>' . htmlspecialchars($fetch['e_description']) . '</p>';
                            echo '</div>';
                            echo '<div class="card-footer">';
                            echo '<p> From: ' . htmlspecialchars($fetch['start_date']) . '</p>';
                            echo '<p> To:   ' . htmlspecialchars($fetch['end_date']) . '</p>';
                            echo '</div>';
                            
                            echo '</div>';
                            echo '</div>';
                        }
                    }else{
                        echo '<p>No events found</p>';
                    }
                ?>

                </div>

        </div>

</section>

</body>
</html>