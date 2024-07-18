<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GalleryCafe</title>
    <style>
        body {
            margin: 0; /* Remove default margin */
            padding: 0; /* Remove default padding */
            background-image: url('../asset/place-pic.jpg');
           
            background-size: cover; /* Cover the entire background */
            background-repeat: no-repeat; /* Prevent background from repeating */
            font-family: Arial, sans-serif; /* Optional: Set a font */
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full height of viewport */
            gap: 2rem;
        }

        .card {
            display: flex;
            justify-content: center;
            width: 200px;
            align-items: center;
            text-align: center;
            height: 300px;
            color: beige;
            display: flex;
            flex-direction: column;
            background: linear-gradient(175deg, #FFC95F, #862B0D), url(../asset/noice.svg);
            background-blend-mode: overlay; /* Optional, to blend the image and gradient */
            padding: 32px;
            border-radius: 2rem;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5); /* Add a subtle shadow */
            transition: all 300ms ease; /* Add a transition effect */
            cursor: pointer; /* Add a pointer cursor on hover */
        }
            a.card {
                text-decoration: none;
            }
            .card:hover {
            transform: scale(1.05); /* Scale the card on hover */
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.7); /* Increase the shadow on hover */
        }

        .card h2 {
            margin-bottom: 15px;
            font-weight: bold; /* Make the heading bold */
            color: #FFC95F; /* Match the heading color with the gradient */
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="../admin/admin-log.php" class="card">
            <h2>Are you an Admin?</h2>
        </a>
        <a href="signup.php" class="card">
            <h2>Are you a Customer?</h2>
        </a>
    </div>
</body>
</html>