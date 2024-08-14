<?php
session_start();
include('../../connection/connection.php');
$success = false; // Initialize the success variable

// Retrieve pending orders
$stmt_order = $conn->prepare("SELECT * FROM pre_order WHERE status = 'pending'");
$stmt_order->execute();
$pending_orders = $stmt_order->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/main-folder/admin/template-admin.css">
    <link rel="stylesheet" href="../../general/home.css">
 <link rel="stylesheet" href="pre-order.css">
    
   
    <script src="../../general/common.js" defer></script>
    <title>staff</title>
</head>
<body>
    <?php include('../opr_template.php');?>
    <section class="home-section">
        <div class="home-content">
            <i class="bx bx-menu"></i>
            <span class="text">Manage Pre-Order</span>
        </div>
        <div class="next">
            <div class="pre-order">
                <h2>Pending Pre-Orders</h2>
                <?php
                $sql = "SELECT * FROM pre_order where status = 'pending'";
                $result = $conn->query($sql);
                //table to diplay pre-order
                if ($result->num_rows > 0) {
                    echo "<table>";
                    echo "<tr>
                        <th>Id</th>
                        <th>User_Id</th>
                        <th>Visiting Date</th>
                        <th>Action</th>
                        <tr>";

                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['user_id'] . "</td>";
                        echo "<td>" . $row['visiting_date'] . "</td>";
                        echo "<td>
                                <form action='pre-order-check-opr.php' method='post'>
                                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                                    <input type='hidden' name='user_id' value='" . $row['user_id'] . "'>
                                    <button type='submit' name='action' value='confirm'>Confirm</button>
                                    <button type='submit' name='action' value='reject'>Reject</button>    
                                </form>
                            </td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No pending Pre-Orders.";
                }
           
                ?>
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
        <?php if (isset($_SESSION['alert'])): ?>
            alert("<?php echo $_SESSION['alert']; ?>");
            <?php unset($_SESSION['alert']); ?> // Clear the session variable after displaying the alert
        <?php endif; ?>
    </script>
</body>
</html>