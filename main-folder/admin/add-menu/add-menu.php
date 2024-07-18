<?php
    session_start(); // Start the session to use session variables
    include('../../connection/connection.php'); // Adjust this path if necessary
    $success = false; // Initialize the success variable
    
    // Check if the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $cuisine = $_POST["cuisine"];
        $type = $_POST["type"];
        $item = $_POST["item"];
        $price = $_POST["price"];
        $description = $_POST["description"];
        $vegan = isset($_POST["Vegan"]) ? 1 : 0;

        // Handle the image upload
        $img_dr = $_FILES["img_dr"];
        $img_dr_name = $img_dr["name"];
        $img_dr_tmp_name = $img_dr["tmp_name"];
        $img_dr_size = $img_dr["size"];
        $img_dr_type = $img_dr["type"];

        // Check if the image is valid
        if ($img_dr_size > 0 && ($img_dr_type == "image/jpeg" || $img_dr_type == "image/png")) {
            $upload_dir = __DIR__ . "/uploads/";
            $img_dr_path = $upload_dir . $img_dr_name;
            
            // Create the uploads directory if it doesn't exist
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Move the uploaded file to the uploads directory
            move_uploaded_file($img_dr_tmp_name, $img_dr_path);

            // Insert the data into the database
            $stmt = $conn->prepare("INSERT INTO menu (Cuisine, Type, Item, Price, Description, img_dr, Vegan) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssi", $cuisine, $type, $item, $price, $description, $img_dr_path, $vegan);
            $stmt->execute();

            // Check if the data was inserted successfully
            if ($stmt->affected_rows > 0) {
                $success = true;
                // Set a session variable for displaying the alert
                $_SESSION['alert'] = "Menu item added successfully!";
                // Redirect to the same page to prevent form resubmission
                header("Location: add-menu.php");
                exit();
            } else {
                echo "Error adding menu item: " . $stmt->error;
            }
        } else {
            echo "Invalid image file";
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
    <link rel="stylesheet" href="add-menu.css">
    <script src="../../general/common.js" defer></script>
    <title>Admin</title>
</head>
<body>
    <?php include('../template_admin.php'); ?>
    
    <section class="home-section">
        <div class="home-content">
            <i class="bx bx-menu"></i>
            <span class="text">Add Menu</span>
        </div>
        <div class="next">
            <form action="/main-folder/admin/add-menu/add-menu.php" method="post" enctype="multipart/form-data" class="form-menu">
                <label for="cuisine">Cuisine:</label>
                <input type="text" id="cuisine" name="cuisine" required><br><br>

                <label for="type">Type:</label>
                <input type="text" id="type" name="type" required><br><br>

                <label for="item">Item:</label>
                <input type="text" id="item" name="item" required><br><br>

                <label for="price">Price:</label>
                <input type="number" id="price" name="price" required><br><br>

                <label for="img_dr">Image:</label>
                <input type="file" id="img_dr" name="img_dr" required><br><br>

                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea><br><br>

                <div class="vegan-checkbox">
                    <input type="checkbox" id="Vegan" name="Vegan">
                    <label for="Vegan">Vegan</label>
                </div><br>

                <input type="submit" value="Add Menu Item">
            </form>
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
