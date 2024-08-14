<?php
session_start();
require '../../stripe/vendor/autoload.php'; // Ensure this path is correct

\Stripe\Stripe::setApiKey('sk_test_51PfkINRuK1hMKp4HQq3lsWI69cv3hA2i3Ewjc6Y4AsHFFGz7u22NPgWdwXlmAFSMux3JjJpk28NUFVEJxteRqAM500uUXyvbqF'); // Replace with your Stripe secret key

include('../../connection/connection.php');

$error = '';
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pay_for_order'])) {
    $order_id = $_POST['order_id'];
    $total_amount = $_POST['total_amount'];
    $user_id = $_SESSION['user_id'];

    // Convert total amount to cents as Stripe expects amounts to be in the smallest currency unit
    $total_amount_cents = $total_amount * 100;

    try {
        // Create a PaymentIntent with the amount and currency
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $total_amount_cents,
            'currency' => 'usd',
            'metadata' => ['order_id' => $order_id],
        ]);

        // Pass the client secret to the frontend
        $clientSecret = $paymentIntent->client_secret;

        // Store the PaymentIntent ID in the session for confirmation step
        $_SESSION['payment_intent_id'] = $paymentIntent->id;

    } catch (\Stripe\Exception\ApiErrorException $e) {
        $error = $e->getMessage();
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
    <link rel="stylesheet" href="pre-order.css"> <!-- Ensure your menu-specific CSS is linked -->
    <script src="../../general/common.js" defer></script>
    <title>Pre Order</title>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        /* pay-for-order.css */

        #payment-form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            box-sizing: border-box;
        }

        h2 {
            margin-top: 0;
            font-size: 1.5em;
            color: #333;
        }

        #card-element {
            margin-bottom: 20px;
        }

        #card-element input {
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
        }

        #submit {
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            font-size: 1em;
            cursor: pointer;
            width: 100%;
            box-sizing: border-box;
        }

        #submit:hover {
            background-color: #218838;
        }

        #error-message {
            color: #dc3545;
            margin-top: 10px;
            font-size: 0.875em;
        }

        .amount-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
        }

        .amount-info p {
            margin: 0;
            font-size: 1.2em;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <?php include('../../general/template.php');?>
    <section class="home-section">
        <div class="home-content">
            <i class="bx bx-menu"></i>
            <span class="text">Pre Order</span>
        </div>

        <div class="next">
                    
            <?php if ($error): ?>
                <p>Error: <?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <div id="payment-form">
                <h2>Complete Your Payment</h2>
                <div class="amount-info">
                    <p>Total Amount: Rs<?= htmlspecialchars($_POST['total_amount']) ?></p>
                </div>
                <form id="payment-form" method="post">
                    <div id="card-element"></div>
                    <button type="submit" id="submit">Pay</button>
                    <div id="error-message"></div>
                    <input type="hidden" id="client-secret" value="<?= htmlspecialchars($clientSecret) ?>">
                </form>
            </div>


        </div>

    </section>



    <script>
        // Initialize Stripe
        var stripe = Stripe('pk_test_51PfkINRuK1hMKp4HP0fzRvdYkt6F2GbBa72r1xHCtXfQGlqejYXzrl5nG80RXgFp8HwCYlx4pGO0AIptoJKk5Txm00t20pFbah'); // Replace with your Stripe publishable key
        var elements = stripe.elements();
        var cardElement = elements.create('card');
        cardElement.mount('#card-element');

        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            var clientSecret = document.getElementById('client-secret').value;

            stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: cardElement
                }
            }).then(function(result) {
                if (result.error) {
                    // Show error to your customer
                    var errorMessage = document.getElementById('error-message');
                    errorMessage.textContent = result.error.message;
                } else {
                    // The payment has been processed!
                    if (result.paymentIntent.status === 'succeeded') {
                        window.location.href = 'success.php'; 
                    }
                }
            });
        });
    </script>
</body>
</html>
