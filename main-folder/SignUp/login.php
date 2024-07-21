<?php
session_start();

if (isset($_POST["submit"])) {
    include('../connection/connection.php'); // Adjust this path if necessary

    // Sanitize input to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    // Query the database for the user
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        // Check if the user exists and verify the password
        if ($row && password_verify($password, $row["password"])) {
            $_SESSION["username"] = $username;
            $_SESSION["user_id"] = $row["id"]; // Store user_id in session

            header("Location: /main-folder/general/home.php");
           
        } else {
            // Display an error message if authentication fails
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var modal = document.getElementById('myModal');
                        modal.style.display = 'block';

                        var span = document.getElementsByClassName('close')[0];

                        span.onclick = function() {
                            modal.style.display = 'none';
                        }

                        window.onclick = function(event) {
                            if (event.target == modal) {
                                modal.style.display = 'none';
                            }
                        }
                    });
                </script>";
        }
    } else {
        echo "Error: " . $conn->error;
    }

   
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="common.css">
    
</head>
<body>
    <div class="container">
        <!-- <div class="image">
            <img src="logo.png" alt="image">
        </div> -->
        <div class="container-text">
            <h1 style="font-family: Delicious Handrawn;"> Gallery Café</h1>
            <p id="sub-text">Welcome to a taste of happiness.</p>
        </div>
       
        <div class="box form-box">
            <header>Login</header>
            <form action="login.php" method="post">
                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="field">
                    
                    <input type="submit" class= "btn " name="submit" value="Login" required>
                </div>
                <div class="link" style="color: aliceblue;">
                    Don't have account <a href="signup.php">Sign-up Now</a>
                </div>
               
            </form>
            
        </div>
    </div>
    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class='message'>
                <p>User Does Not Exists</p>
            </div>
            <br>
            <a href='signup.php'><button class='btn-log'>Sign-Up</button></a>
        </div>
    </div>

</body>
</html>