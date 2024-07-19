<?php
    session_start(); // Start the session to use session variables
    include('../../connection/connection.php'); // Adjust this path if necessary
    $success = false; // Initialize the success variable

   

    // Handle form submission
    if (isset($_POST['update'])) {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $cuisine = mysqli_real_escape_string($conn, $_POST['cuisine']);
        $type = mysqli_real_escape_string($conn, $_POST['type']);
        $item = mysqli_real_escape_string($conn, $_POST['item']);
        $price = mysqli_real_escape_string($conn, $_POST['price']);
        $img_dr = mysqli_real_escape_string($conn, $_POST['img_dr']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $vegan = isset($_POST['Vegan']) ? 1 : 0; // Set vegan to 1 if checkbox is checked, otherwise 0

        $sql2 = "UPDATE `menu` SET `Cuisine` = '$cuisine', `Type` = '$type', `Item` = '$item', `Price` = '$price',  `Description` = '$description', `Vegan` = '$vegan' WHERE `id` = '$id'";
        $result2 = mysqli_query($conn, $sql2);

        
        if ($result2) {
            $_SESSION['alert'] = 'Menu item updated successfully!';
            header("Location: edit-menu.php"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION['alert'] = 'Error updating menu item!';
            header("Location: edit-menu.php"); // Redirect to display the error message
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
    <link rel="stylesheet" href="edit-menu.css">
    <script src="../../general/common.js" defer></script>
    <title>Admin</title>
</head>
<body>
    <?php include('../template_admin.php'); ?>
    
    <section class="home-section">
        <div class="home-content">
            <i class="bx bx-menu"></i>
            <span class="text">Edit Menu</span>
        </div>
        <div class="next">
            <div class="cards">
            <?php
             //select menu items
                $sql = "SELECT * FROM `menu`";
                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result)>0 ){
                    while($fetch = mysqli_fetch_assoc($result)){
                                echo '<div class="card">';
                                // Display menu item details as cards
                            echo '<div class="card-content">';
                            // Display image if available
                            if (!empty($fetch['img_dr'])) {
                                // Extract relative path from full image path
                                $relative_path = substr($fetch['img_dr'], strpos($fetch['img_dr'], '\main-folder'));
                                echo '<img src="' . htmlspecialchars($relative_path) . '" alt="' . htmlspecialchars($fetch['Item']) . '">';
                            }
                                echo '<h3>' . htmlspecialchars($fetch['Item']) . '</h3>';
                                echo '<p>Cuisine: ' . htmlspecialchars($fetch['Cuisine']) . '</p>';
                                echo '<p>Price: $' . htmlspecialchars($fetch['Price']) . '</p>';
                                echo '<p> ' . htmlspecialchars($fetch['Description']) . '</p>';
                                echo '</div>'; // Close card-content
                                echo '<div class="card-footer">';
                                echo '<input class="modal-btn" data-id="' . $fetch['id'] . '" data-cuisine="' . $fetch['Cuisine'] . '" data-type="' . $fetch['Type'] . '" data-item="' . $fetch['Item'] . '" data-price="' . $fetch['Price'] . '" data-img_dr="' . $fetch['img_dr'] . '" data-description="' . $fetch['Description'] . '" data-vegan="' . $fetch['Vegan'] . '" type="submit" value="Edit ">';
                                echo '<input type="delete" class="btn" value="Delete" onclick="location.href=\'delete.php?id=' . $fetch['id'] . '\'">';
                                echo '</div>'; // Close card-footer
                                echo '</div>'; // Close card
                            }
                }else{
                    echo '<p>No menu items found.</p>';
                }
                ?>
            </div>
           
            
        </div>
        <!-- Edit pop-up part -->
        <div class="next-content" id="edit-popup">
            <div class="box form-box">
                <form method="POST">
                    <div class="button_cancel">
                        <button type="button" id="close-edit-popup" class="cancel_icon"><i class='bx bx-x'></i></button>
                    </div>
                    
                    <input type="hidden" id="note_id" name="id">
                    
                    <div class="field input">
                        <label for="edit_cuisine">Cuisine:</label>
                        <input type="text" id="edit_cuisine" name="cuisine" placeholder="Your cuisine" autocomplete="off" >
                    </div>
                    
                    <div class="field input">
                        <label for="edit_type">Type:</label>
                        <input type="text" id="edit_type" name="type" placeholder="Your type" autocomplete="off" >
                    </div>
                    
                    <div class="field input">
                        <label for="edit_item">Item:</label>
                        <input type="text" id="edit_item" name="item" placeholder="Your item" autocomplete="off" >
                    </div>
                    
                    <div class="field input">
                        <label for="edit_price">Price:</label>
                        <input type="number" id="edit_price" name="price" placeholder="Your price" autocomplete="off" >
                    </div>
                    
                    <div class="field input">
                        <label for="edit_img_dr">Image:</label>
                        <input type="file" id="edit_img_dr" name="img_dr" placeholder="Your image" autocomplete="off" >
                    </div>
                    
                    <div class="field input">
                        <label for="edit_description">Description:</label>
                        <textarea id="edit_description" name="description" rows="4" autocomplete="off" placeholder="your description..."></textarea>
                    </div>
                    
                    <div class="vegan-checkbox">
                        <input type="checkbox" id="edit_Vegan" name="Vegan">
                        <label for="edit_Vegan">Vegan</label>
                    </div><br>
                    <div class="field-btn">
                        <input type="submit" class="btn1" name="update" value="Update">
                        <input type="reset" class="btn1" name="cancel" value="Cancel"> 
                    </div>
                </form>
            </div>
        </div>

</section>

  <!-- JavaScript for edit pop-up -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const editButtons = document.querySelectorAll('.modal-btn');
        const editPopup = document.getElementById('edit-popup');
        const closeEditPopupButton = document.getElementById('close-edit-popup');
        const noteIdInput = document.getElementById('note_id');
        const editCuisineInput = document.getElementById('edit_cuisine');
        const editTypeInput = document.getElementById('edit_type');
        const editItemInput = document.getElementById('edit_item');
        const editPriceInput = document.getElementById('edit_price');
    
        const editDescriptionInput = document.getElementById('edit_description');
        const editVeganInput = document.getElementById('edit_Vegan');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const cuisine = this.getAttribute('data-cuisine');
                const type = this.getAttribute('data-type');
                const item = this.getAttribute('data-item');
                const price = this.getAttribute('data-price');
               
                const description = this.getAttribute('data-description');
                const vegan = this.getAttribute('data-vegan');

                noteIdInput.value = id;
                editCuisineInput.value = cuisine;
                editTypeInput.value = type;
                editItemInput.value = item;
                editPriceInput.value = price;
                
                editDescriptionInput.value = description;
                editVeganInput.checked = (vegan == 1);

                editPopup.classList.add('active');
            });
        });

        closeEditPopupButton.addEventListener('click', function() {
            editPopup.classList.remove('active');
        });
    });
      // Close the modal if the user clicks outside of it
      window.onclick = function(event) {
        if (event.target == box) {
            box.style.display = "none";
        }
    }
    <?php if (isset($_SESSION['alert'])): ?>
            alert("<?php echo $_SESSION['alert']; ?>");
            <?php unset($_SESSION['alert']); ?> // Clear the session variable after displaying the alert
        <?php endif; ?>
</script>

</body>

</html>