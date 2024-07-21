<?php
session_start();
include('../../connection/connection.php');
$success = false;

//handle the evnt submission
if(isset($_POST['update'])){
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $event_name = mysqli_real_escape_string($conn, $_POST['e_name']);
    $event_description = mysqli_real_escape_string($conn, $_POST['e_description']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);

    $sql = "UPDATE `events` SET `e_name` = '$event_name', `e_description` = '$event_description', `start_date` = '$start_date', `end_date` = '$end_date' WHERE `id` = '$id'";
    $result = mysqli_query($conn, $sql);

    if($result){
        $_SESSION['alert'] = 'Event updated successfully!';
        header("Location: edit_event.php"); // Redirect to avoid form resubmission
        exit();
    } else {
        $_SESSION['alert'] = 'Error updating event!';
        header("Location: edit_event.php"); // Redirect to display the error message
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
    <link rel="stylesheet" href="/main-folder/admin/add-menu/add-menu.css">
    <link rel="stylesheet" href="add-specialEvents.css">
    <script src="../../general/common.js" defer></script>
    <title>Admin</title>
</head>
<body>
    <?php include('../template_admin.php'); ?>
    
    <section class="home-section">
        <div class="home-content">
            <i class="bx bx-menu"></i>
            <span class="text">Edit Events</span>
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
                            echo '<h3>' . htmlspecialchars($fetch['e_name']) . '</h3>';
                            echo '<p>' . htmlspecialchars($fetch['e_description']) . '</p>';
                            echo '<p> From: ' . htmlspecialchars($fetch['start_date']) . '</p>';
                            echo '<p> To:   ' . htmlspecialchars($fetch['end_date']) . '</p>';
                            echo '</div>';
                            echo '<div class="card-footer">';
                            echo '<input class="modal-btn" data-id="' . $fetch['id'] . '" data-e_name="' . $fetch['e_name'] . '" data-e_description="' . $fetch['e_description'] . '" data-start_date="' . $fetch['start_date'] . '" data-end_date="' . $fetch['end_date'] . '" type="submit" value="Edit" name="edit" >';
                            echo '<input type="delete" class="btn" value="Delete" onclick="location.href=\'e_delete.php?id=' . $fetch['id'] . '\'">';
                            echo '</div>';
                            echo '</div>';
                        }
                    }else{
                        echo '<p>No events found</p>';
                    }
                ?>

                </div>
        </div>
        <!-- edit pop-up modal -->
         <div class="next-content" id="edit-popup">
            <div class="box form-Event">
                <form method="POST">
                        <div class="button_cancel">
                            <button type="button" id="close-edit-popup" class="cancel_icon"><i class='bx bx-x'></i></button>
                            <input type="hidden" id="event_id" name="id">
                        </div>
                        <div class="field input">
                            <label for="edit_name">Event Name:</label>
                            <input type="text" id="edit_name" name="e_name" placeholder="Enter event name..." autocomplete="off">
                        </div>
                        <div class="field input">
                            <label for="edit_description">Event Description:</label>
                            <textarea type="text" id="edit_description" name="e_description" placeholder="Enter event description..." autocomplete="off"></textarea>
                        </div>
                        <div class="field input">
                            <label for="edit_start_date">Start Date:</label>
                            <input type="date" id="edit_start_date" name="start_date" autocomplete="off">
                        </div>
                        <div class="field input">
                            <label for="edit_end_date">End Date:</label>
                            <input type="date" id="edit_end_date" name="end_date" autocomplete="off">
                        </div>
                        <div class="field-btn">
                            <input type="submit" class="btn1" name="update" value="Update">
                            <input type="reset" class="btn1" name="cancel" value="Cancel">
                        </div>
                        
                   
                </form>
            </div>
         </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editBtns = document.querySelectorAll('.modal-btn');
            const editPopup = document.getElementById('edit-popup');
            const closeEditPopupButton = document.getElementById('close-edit-popup');
            const editName = document.getElementById('edit_name');
            const editDescription = document.getElementById('edit_description');
            const editStartDate = document.getElementById('edit_start_date');
            const editEndDate = document.getElementById('edit_end_date');
            const eventId = document.getElementById('event_id');

            editBtns.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const e_name = this.getAttribute('data-e_name');
                    const e_description = this.getAttribute('data-e_description');
                    const start_date = this.getAttribute('data-start_date');
                    const end_date = this.getAttribute('data-end_date');

                    editName.value = e_name;
                    editDescription.value = e_description;
                    editStartDate.value = start_date;
                    editEndDate.value = end_date;
                    eventId.value = id;

                    editPopup.classList.add('active');
                });
            });

            closeEditPopupButton.addEventListener('click', function() {
                editPopup.classList.remove('active');
            });
        })
        // Check if the session variable 'alert' is set and display the alert
        <?php if (isset($_SESSION['alert'])): ?>
            alert("<?php echo $_SESSION['alert']; ?>");
            <?php unset($_SESSION['alert']); ?> // Clear the session variable after displaying the alert
        <?php endif; ?>
    </script>
</body>
</html>