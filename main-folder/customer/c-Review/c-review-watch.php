<?php
session_start();
include('../../connection/connection.php'); // Adjust the path as necessary

$user_id = $_SESSION['user_id'];

// Fetch menu items from the database
$menu_query = "SELECT * FROM `menu`";
$menu_result = mysqli_query($conn, $menu_query);

$reviews = [];
$selected_food = '';

if (isset($_GET['food']) && !empty($_GET['food'])) {
    $selected_food = $_GET['food'];

    // Fetch reviews for the selected menu item
    $stmt = $conn->prepare("SELECT user_id, menu_item, review, created_at FROM review WHERE menu_item = ?");
    $stmt->bind_param("s", $selected_food);
    $stmt->execute();
    $result = $stmt->get_result();
    $reviews = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../general/template.css">
    <link rel="stylesheet" href="../../general/home.css">
    <link rel="stylesheet" href="watch.css"> <!-- Ensure your menu-specific CSS is linked -->
    <!-- Include the JavaScript file -->
    <script src="../../general/common.js" defer></script>
    <title>Review</title>
</head>
<body>
    <?php include('../../general/template.php'); ?>
    
    <section class="home-section">
        <div class="home-content">
            <i class="bx bx-menu"></i>
            <span class="text">Review</span>
        </div>
       
        <div class="next">
            <div class="search-form">
                <form action="" method="GET">
                    <select name="food" id="food">
                        <option value="">Select Menu Item</option>
                        <?php
                        // Populate the dropdown with menu items
                        while($menu_result_row = mysqli_fetch_assoc($menu_result)){
                            $selected = ($menu_result_row['Item'] === $selected_food) ? 'selected' : '';
                            echo '<option value="'. $menu_result_row['Item']. '" '. $selected . '>'. $menu_result_row['Item']. '</option>';
                        }
                        ?>
                    </select><br>
                    <input type="submit" value="Filter">
                </form>
            </div>

            <!-- Display reviews for the selected menu item -->
            <div class="reviews">
                <h3>Reviews for <?= htmlspecialchars($selected_food) ?></h3>
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="review">
                            <p><strong>Menu Item:</strong> <?= htmlspecialchars($review['menu_item']) ?></p>
                            <p><strong>Review:</strong> <?= htmlspecialchars($review['review']) ?></p>
                            <p><small><strong>Date:</strong> <?= htmlspecialchars($review['created_at']) ?></small></p>
                            <p><small><strong>User:</strong> <?= htmlspecialchars($review['user_id']) ?></small></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No reviews available for this menu item.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

</body>
</html>
