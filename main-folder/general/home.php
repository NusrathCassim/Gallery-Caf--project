<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script type="text/javascript">
        function preventBack() {
            window.history.forward();
        };
        setTimeout("preventBack()", 0);
        window.onunload = function() {null;}
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="template.css">
    <link rel="stylesheet" href="home.css">
</head>
<body>
<?php include('template.php'); ?>

<section class="home-section">
        <div class="home-content">
            <i class="bx bx-menu"></i>
            <span class="text">Welcome  <?php echo $_SESSION["username"];?>  </span>
           
            
        </div>
       
    </section>

</body>
<script src="common.js"></script>
</html>