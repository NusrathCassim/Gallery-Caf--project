<?php
session_start();

// Connection to the database
include('../../connection/connection.php');

// Initialize variables
$success = false;
$error = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_pre_order'])) {
    if (isset($_SESSION['user_id'])) {
        $visiting_date = $_POST['visiting_date'];
        $menu_items = $_POST['menu_items'] ?? [];
        $quantities = $_POST['quantities'] ?? [];
        $menu_item_names = $_POST['menu_item_names'] ?? [];
        $user_id = $_SESSION['user_id'];

        // Insert into pre_order table
        $stmt = $conn->prepare("INSERT INTO pre_order (user_id, visiting_date) VALUES (?,?)");
        $stmt->bind_param("is", $user_id, $visiting_date);
        $stmt->execute();
        $order_id = $conn->insert_id;

        // Insert into order_items table
        foreach ($menu_items as $menu_item_id) {
            $quantity = $quantities[$menu_item_id];
            $item_name = $menu_item_names[$menu_item_id];
            $stmt = $conn->prepare("SELECT Price FROM menu WHERE id = ?");
            $stmt->bind_param("i", $menu_item_id);
            $stmt->execute();
            $menu_item_price = $stmt->get_result()->fetch_assoc()['Price'];

            $stmt = $conn->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity, price) VALUES (?,?,?,?)");
            $stmt->bind_param("iiii", $order_id, $menu_item_id, $quantity, $menu_item_price);
            $stmt->execute();
        }

        $success = true;
        $_SESSION['alert'] = "Order added successfully!";
        header("Location: pre-order.php");
        exit();
    } else {
        echo "User ID not found in session.";
    }
}

// Retrieve menu items
$stmt = $conn->prepare("SELECT id, Item, Price, img_dr FROM menu");
$stmt->execute();
$menu_items = $stmt->get_result();

// Retrieve pending orders
$stmt_order = $conn->prepare("SELECT * FROM pre_order WHERE user_id =? and status = 'pending'");
$stmt_order->bind_param("i", $_SESSION['user_id']);
$stmt_order->execute();
$pending_orders = $stmt_order->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../general/template.css">
    <link rel="stylesheet" href="../../general/home.css">
    <link rel="stylesheet" href="pre-order.css"> <!-- Ensure your menu-specific CSS is linked -->
    <script src="../../general/common.js" defer></script>
    <title>Pre Order</title>
</head>
<body>
    <?php include('../../general/template.php');?>

    <section class="home-section">
        <div class="home-content">
            <i class="bx bx-menu"></i>
            <span class="text">Pre Order</span>
        </div>

        <div class="next">
            <!-- Display menu items with a checkbox -->
            <!-- User can select multiple menu items -->
            <div class="form_cont">
                <form action="pre-order.php" method="post">
                    <div class="display_menu">
                        <?php
                        echo '<label for="visiting_date">Choose visiting day:</label>';
                        echo "<input type='date' id='visiting_date' name='visiting_date' required>";

                        foreach ($menu_items as $menu_item) {
                            $menu_id = $menu_item['id'];
                            $item = $menu_item['Item'];
                            $price = $menu_item['Price'];

                            echo "<div class='menu_item'>";
                            if (!empty($menu_item['img_dr'])) {
                                $relative_path = substr($menu_item['img_dr'], strpos($menu_item['img_dr'], '\main-folder'));
                                echo '<img src="'. htmlspecialchars($relative_path). '" alt="'. htmlspecialchars($menu_item['Item']). '">';
                            }
                            echo "<label class='menu_item_label'>";
                            echo "<span class='menu_item_text'>";
                            echo "<span class='menu_item_name'>$item</span>";
                            echo "<span class='menu_item_price'>$$price</span>";
                            echo "<input type='hidden' name='menu_item_names[$menu_id]' value='$item'>";

                            echo "<div class='insert'>";
                            echo "<input type='checkbox' name='menu_items[]' value='$menu_id' class='menu_item_checkbox'>";
                            echo "<input type='number' name='quantities[$menu_id]' min='1' max='10' value='1' class='menu_item_quantity'>";
                            echo "</div>";
                            echo "</span>";
                            echo "</label>";
                            echo "</div>";
                        }
                       ?>
                    </div>

                    <div class="preorder_details">
                        <input type="submit" name="submit_pre_order" class="btn" value="Submit Pre-order">
                        <?php if ($success) {
                            echo "<p>Order added successfully!</p>";
                        } elseif (!empty($error)) {
                            echo "<p>$error</p>";
                        }?>
                    </div>
                </form>
            </div>
            <div class="pending_orders">
                <!-- Display pending orders in customer page -->
                <?php if ($pending_orders->num_rows > 0): ?>
                    <div class="cards">
                        <?php foreach ($pending_orders as $order): ?>
                            <div class="card">
                                <p>Details: 
                                    <?php
                                    // Select the order items based on order id
                                    $order_id = $order['id'];
                                    $stmt_items = $conn->prepare("SELECT oi.quantity, oi.price, m.Item FROM order_items oi JOIN menu m ON oi.menu_item_id = m.id WHERE oi.order_id = ?");
                                    $stmt_items->bind_param("i", $order_id);
                                    $stmt_items->execute();
                                    $order_items = $stmt_items->get_result();

                                    $total_amount = 0;
                                    while ($item = $order_items->fetch_assoc()) {
                                        $item_name = $item['Item'];
                                        $quantity = $item['quantity'];
                                        $price = $item['price'];
                                        $total_price = $price * $quantity;
                                        $total_amount += $total_price;

                                        echo "$item_name (Quantity: $quantity, Price: $$price, Total: $$total_price)<br>";
                                    }
                                    ?>
                                </p>
                                <p>Order ID: <?='GC_ON'. $order['id']?> </p>
                                <p>Visiting Date: <?= $order['visiting_date']?></p>
                                <p id='status'>Order Status: <?= $order['status']?></p>
                                <p>Total Amount: $<?= $total_amount ?></p>
                                <!-- Add this to your existing form -->
                                <form action="pay_for_order.php" method="post">
                                    <input type="hidden" name="total_amount" value="<?= $total_amount ?>">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <input type="submit" name="pay_for_order" class="btn" value="Pay for Order">
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No pending orders found.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <script>
        // Check if the session variable 'alert' is set and display the alert
        <?php if (isset($_SESSION['alert'])):?>
            alert("<?php echo $_SESSION['alert'];?>");
            <?php unset($_SESSION['alert']);?> // Clear the session variable after displaying the alert
        <?php endif;?>
    </script>
</body>
</html>
