<?php
session_start();
include('../../connection/connection.php'); // Adjust the path as necessary

// Fetch menu items from the database
$query = "SELECT * FROM `menu`";
$result = mysqli_query($conn, $query);

// Search functionality
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $query = "SELECT * FROM `menu` WHERE `Item` LIKE '%" . $search . "%'";
    $result = mysqli_query($conn, $query);
}

// Filter by cuisine
if (isset($_GET['cuisine'])) {
    $cuisine = mysqli_real_escape_string($conn, $_GET['cuisine']);
    $query = "SELECT * FROM `menu` WHERE `Cuisine` = '$cuisine'";
    $result = mysqli_query($conn, $query);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../general/template.css">
    <link rel="stylesheet" href="../../general/home.css">
    <link rel="stylesheet" href="menu.css"> <!-- Ensure your menu-specific CSS is linked -->
    <!-- Include the JavaScript file -->
    <script src="../../general/common.js" defer></script>
    <title>Menu</title>
</head>
<body>
    <?php include('../../general/template.php'); ?>
    
    
    <section class="home-section">
        <div class="home-content">
            <i class="bx bx-menu"></i>
            <span class="text">Menu</span>
        </div>
       
        <div class="next">
            <div class="mainform">
                <div class="search-form">
                    <form action="" method="GET">
                        <input type="text" name="search" placeholder="Search by item name...">
                        <input type="submit" value="Search">
                    </form>
                </div>
                <div class="cuisine-form">
                    <form action="" method="GET">
                        <select name="cuisine" class="cuisine-select">
                            <option value="">Select Cuisine</option>
                            <?php
                            // Fetch cuisines from the database
                            $cuisine_query = "SELECT DISTINCT `Cuisine` FROM `menu`";
                            $cuisine_result = mysqli_query($conn, $cuisine_query);
                            while ($cuisine_row = mysqli_fetch_assoc($cuisine_result)) {
                                echo '<option value="'. $cuisine_row['Cuisine']. '">'. $cuisine_row['Cuisine']. '</option>';
                            }
                            ?>
                        </select>
                        <input type="submit" value="Filter">
                    </form>
                </div>

            </div>
            
            <div class="cards">
            <?php
            // Check if there are any results
            if (mysqli_num_rows($result) > 0) {
                // Output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    // Display menu item details as cards
                    echo '<div class="card">';
                    // Display image if available
                    if (!empty($row['img_dr'])) {
                         // Extract relative path from full image path
                         $relative_path = substr($row['img_dr'], strpos($row['img_dr'], '\main-folder'));
                         echo '<img src="' . htmlspecialchars($relative_path) . '" alt="' . htmlspecialchars($row['Item']) . '">';
                    }
                    echo '<h3>' . htmlspecialchars($row['Item']) . '</h3>';
                    echo '<p>Cuisine: ' . htmlspecialchars($row['Cuisine']) . '</p>';
                    echo '<p>Price: $' . htmlspecialchars($row['Price']) . '</p>';
                    echo '<p> ' . htmlspecialchars($row['Description']) . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p>No menu items found.</p>';
            }
            ?>

            </div>
            
        </div>
    </section>
    <script>
        // Pre-select cuisine option if it's in the query string
        var urlParams = new URLSearchParams(window.location.search);
        var cuisineParam = urlParams.get('cuisine');
        if (cuisineParam) {
            var select = document.querySelector('.cuisine-select');
            select.value = cuisineParam;
        }
    </script>
</body>
</html>