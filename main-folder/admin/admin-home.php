<?php
session_start();
include('../connection/connection.php');
$success = false;

$query = $conn->query("
    SELECT 
        COUNT(CASE WHEN status = 'confirmed' THEN 1 END) as c_orders,
        COUNT(CASE WHEN status = 'pending' THEN 1 END) as p_orders,
        COUNT(CASE WHEN status = 'rejected' THEN 1 END) as r_orders
    FROM pre_order
");
$c_orders = 0;
$p_orders = 0;
$r_orders = 0;

if ($query) {
    $data = $query->fetch_assoc();
    $c_orders = $data['c_orders'];
    $p_orders = $data['p_orders'];
    $r_orders = $data['r_orders'];
} else {
    echo "Error: " . $conn->error;
}

// JSON encode the data
$order_counts = json_encode([$c_orders, $p_orders, $r_orders]);
$query = $conn->query("Select
        COUNT(CASE WHEN status = 'confirmed' THEN 1 END) as c_res,
        COUNT(CASE WHEN status = 'pending' THEN 1 END) as p_res,
        COUNT(CASE WHEN status = 'rejected' THEN 1 END) as r_res
      FROM reservations  
");
$c_res = 0;
$p_res = 0;
$r_res = 0;
if ($query) {
    $data = $query->fetch_assoc();
    $c_res = $data['c_res'];
    $p_res = $data['p_res'];
    $r_res = $data['r_res'];
} else {
    echo "Error: " . $conn->error;
}
$reservation_data = json_encode([$c_res, $p_res, $r_res]);
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
    <link rel="stylesheet" href="../general/template.css">
    <link rel="stylesheet" href="admin-home.css">
    
    <link rel="stylesheet" href="../general/home.css">
    
</head>
<body>
<?php include('template_admin.php'); ?>

<section class="home-section">
        <div class="home-content">
            <i class="bx bx-menu"></i>
            <span class="text">Welcome </span>

            
        </div>
        <div class="next">
            <!-- main card -->
            <div class="card_set">
                <div class="card">
                    <div class="header">
                    <i class='bx bxs-user-circle'></i>
                    <h2>No of Users</h2>
                    <?php
                        $stmt = $conn->prepare("SELECT COUNT(*) FROM users");
                        $stmt->execute();
                        $stmt->bind_result($count);
                        $stmt->fetch();
                        
                        echo "<span class='counter'>"  .$count;"</span>";
                    
                        $stmt->close();
                    ?>
                    
                    </div>
                    
                    
                </div>
                <div class="card">
                    <div class="header">
                    <i class='bx bxs-food-menu'></i>
                    <h2>Menu Items</h2>
                    <?php
                        $stmt = $conn->prepare("SELECT COUNT(*) FROM menu");
                        $stmt->execute();
                        $stmt->bind_result($count);
                        $stmt->fetch();

                        echo "<span class='counter'>"  .$count; "</span>";
                    
                        $stmt->close();
                    ?>
                    
                    </div>
                    
                   
                </div>
                <div class="card">
                    <div class="header">
                        <i class='bx bxs-cube-alt'></i>
                        <h2>No of Tables</h2>
                        <?php
                        $stmt = $conn->prepare("SELECT COUNT(*) FROM res_table");
                        $stmt->execute();
                        $stmt->bind_result($count);
                        $stmt->fetch();

                        echo "<span class='counter'>"  .$count; "</span>";
                    
                        $stmt->close();
                    ?>
                    </div>
                   
                    
                </div>
            </div>
            <!-- graph -->
             <div class="graphbox">
                <div class="box">
                    <canvas id="myChart" width></canvas>
                </div>
                <div class="box">
                <canvas id="myChartReservation" width></canvas>
                </div>
             </div>
            <!-- table for all reservation details -->
            <div class="other">
                <div class="table_info">
                    <?php
                   
                    $stmt = $conn->prepare("SELECT * FROM reservations");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if($result->num_rows >0){
                        echo "<table>";
                        echo "<tr>";
                        echo "<th>Table No</th>";
                        echo "<th>No of Guests</th>";
                        echo "<th>Date</th>";
                        echo "<th>Time</th>";
                        echo "<th>Status</th>";
                        echo "</tr>";
                        while($row = $result->fetch_assoc()){
                            echo "<tr>";
                            echo "<td>" . $row['table_number'] . "</td>";
                            echo "<td>" . $row['num_guests'] . "</td>";
                            echo "<td>" . $row['reservation_date'] . "</td>";
                            echo "<td>" . $row['reservation_time'] . "</td>";
                            // Translate the status value to a user-friendly text
                            $status_text = '';
                            switch ($row['status']) {
                                case 'pending':
                                    $status_text = 'Pending';
                                    break;
                                case 'confirmed':
                                    $status_text = 'Confirmed';
                                    break;
                                case 'rejected':
                                    $status_text = 'Rejected';
                                    break;
                            }
                            echo "<td>" . $status_text . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    }else{
                        echo "No reservations found";
                    }

                    ?>
                </div>
                <!-- //count -->
                 <div class="card_set2">
                    <div class="card2">
                        <div class="header">
                        <i class='bx bxs-check-circle'></i>
                        <h2>confirmed reservations</h2>
                        <?php
                            $stmt = $conn->prepare("SELECT COUNT(*) FROM reservations where status = 'confirmed'");
                            $stmt->execute();
                            $stmt->bind_result($count);
                            $stmt->fetch();
                            
                            echo "<span class='counter'>"  .$count;"</span>";
                        
                            $stmt->close();
                        ?>
                        
                        </div>
                        
                        
                    </div>
                    <div class="card2">
                        <div class="header">
                        <i class='bx bxs-time-five'></i>
                        <h2>Pending reservations</h2>
                        <?php
                            $stmt = $conn->prepare("SELECT COUNT(*) FROM reservations where status = 'pending'");
                            $stmt->execute();
                            $stmt->bind_result($count);
                            $stmt->fetch();
                            
                            echo "<span class='counter'>"  .$count;"</span>";
                        
                            $stmt->close();
                        ?>
                        
                        </div>
                        
                        
                    </div>
                    <div class="card2">
                        <div class="header">
                        <i class='bx bxs-x-circle' ></i>
                        <h2>Rejected reservations</h2>
                        <?php
                            $stmt = $conn->prepare("SELECT COUNT(*) FROM reservations where status = 'rejected'");
                            $stmt->execute();
                            $stmt->bind_result($count);
                            $stmt->fetch();
                            
                            echo "<span class='counter'>"  .$count;"</span>";
                        
                            $stmt->close();
                        ?>
                        
                        </div>
                        
                        
                    </div>
                </div>
            </div>
            
        </div>
       
</section>

</body>
<script src="../general/common.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- charts -->
<script>
        // Get the data from PHP
        var orderCounts = <?php echo $order_counts; ?>;

        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'polarArea',
            data: {
                labels: ['Confirmed', 'Pending', 'Rejected'], // X-axis labels
                datasets: [{
                    label: 'Order Status',
                    data: orderCounts, // Y-axis data
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
               y: {
                    beginAtZero: true
               }
            }
        });
</script>
<script>
        // Get the data from PHP
        var orderCounts = <?php echo $reservation_data; ?>;

        var ctx = document.getElementById('myChartReservation').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Confirmed', 'Pending', 'Rejected'], // X-axis labels
                datasets: [{
                    label: 'Order Status',
                    data: orderCounts, // Y-axis data
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
               y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                    }
               }
            }
        });
</script>
</html>