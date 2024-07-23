<?php
session_start();
include('../../connection/connection.php'); // Adjust the path as necessary

$user_id = $_SESSION['user_id'];
$error = '';

// Handle review submission
if (isset($_POST['submit'])) {
    $menu_item = $_POST['food'];
    $review = $_POST['review'];

    if (!empty($menu_item) && !empty($review)) {
        $stmt = $conn->prepare("INSERT INTO review (user_id, menu_item, review) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $menu_item, $review);
        
        if ($stmt->execute()) {
            $_SESSION['alert'] = "Review added successfully!";
            header("Location: c-review.php");
            exit();
        } else {
            $error = "Error adding review. Please try again.";
        }
        
        $stmt->close();
    } else {
        $error = "Please fill in all fields.";
    }
}

// Handle review deletion
if (isset($_POST['delete'])) {
    $review_id = $_POST['review_id'];

    $stmt = $conn->prepare("DELETE FROM review WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $review_id, $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['alert'] = "Review deleted successfully!";
        header("Location: c-review.php");
        exit();
    } else {
        $error = "Error deleting review. Please try again.";
    }
    
    $stmt->close();
}

// Fetch reviews from the database
$query = "SELECT id, menu_item, review, created_at FROM review WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$reviews = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../general/template.css">
    <link rel="stylesheet" href="../../general/home.css">
    <link rel="stylesheet" href="c-review.css"> <!-- Ensure your menu-specific CSS is linked -->
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
        <div class="form_data">
                <form action="c-review.php" method="post" enctype="multipart/form-data" class="form-book">
                    <div class="form-row">
                        <select name="food" id="food">
                            <option value="">Select Menu Item</option>
                            <?php
                            // Fetch menu items from the database
                            $query = "SELECT * FROM `menu`";
                            $result = mysqli_query($conn, $query);
                            while($menu_result_row= mysqli_fetch_assoc($result)){
                                echo '<option value="'. $menu_result_row['Item']. '">'. $menu_result_row['Item']. '</option>';
                            }
                            ?>
                        </select><br>
                        <label for="review">Review:</label>
                        <textarea name="review" id="review" required></textarea>
                    
                
                        <div class="btn_box">
                        <input type="submit" name="submit" value="Add Review">
                        <input type="reset" class="btn1" name="cancel" value="Cancel"> 

                        </div>
                    </div>   
                </form>
                <?php
                if (isset($error)) {
                    echo "<p>$error</p>";
                }
                ?>
            </div>

            <!-- Display user reviews -->
            <div class="reviews">
                <h3>Your Reviews</h3>
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="review">
                            <p><strong>Menu Item:</strong> <?= htmlspecialchars($review['menu_item']) ?></p>
                            <p><strong>Review:</strong> <?= htmlspecialchars($review['review']) ?></p>
                            <p><small><strong>Date:</strong> <?= htmlspecialchars($review['created_at']) ?></small></p>
                            <form action="c-review.php" method="post" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                                <input type="submit" name="delete" value="Delete" class="btn-delete">
                            </form>
                        </div>
                        
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>You haven't added any reviews yet.</p>
                <?php endif; ?>
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
