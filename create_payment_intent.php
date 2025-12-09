<?php

//STRIPE API
require 'vendor/autoload.php';
\Stripe\Stripe::setApiKey('sk_test_51ScI2MP2ITnf2U5Mqp4t9m8RXV91jrKyq4CPFnYsuLz9QHdPNQu3sOJ98KgASSTpI2nFoNzvbEv04Rg0J1pzNcfd00pbsXOJ73');

// DATABASE CONNECTION START
$serverName = "RaileyLAPTOP";
$connectionOptions = [
    "Database" => "PROJECT",
    "Uid" => "",
    "PWD" => ""
];
$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
// DATABASE CONNECTION END

$REGION = $_POST['REGION'];
$CITY = $_POST['CITY'];
$HOTEL = $_POST['HOTEL'];
$FULL_NAME = $_POST['FULL_NAME'];
$EMAIL = $_POST['EMAIL'];
$CHECK_IN = $_POST['CHECK_IN'];
$CHECK_OUT = $_POST['CHECK_OUT'];
$GUESTS = $_POST['GUESTS'];
$PRICE_PER_NIGHT = $_POST['PRICE_PER_NIGHT'];





// https://www.php.net/manual/en/datetime.construct.php
$checkInDate  = new DateTime($CHECK_IN);
$checkOutDate = new DateTime($CHECK_OUT);

// https://www.php.net/manual/en/datetime.diff.php
$days = $checkOutDate->diff($checkInDate)->days;





// Stripe API https://docs.stripe.com/api/payment_intents
$totalAmount  = $PRICE_PER_NIGHT * $days * 100;

try {
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $totalAmount,
        'currency' => 'php',
        'description' => "Booking: $HOTEL, $CITY, $REGION",
        'receipt_email' => $EMAIL
    ]);
} catch (\Stripe\Exception\ApiErrorException $e) {
    die("Stripe Error: " . $e->getMessage());
}

?>





<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Booking Payment - <?php echo $HOTEL; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Complete Your Booking Payment</h1>
        <h4><?php echo $HOTEL; ?> - <?php echo $CITY; ?></h4>
        <p>Guest: <?php echo $FULL_NAME; ?></p>
        <p>Total Amount: ₱<?php echo number_format($PRICE_PER_NIGHT * $days, 2); ?> for <?php echo $days; ?> night(s)</p>

        <form id="paymentForm">
            <input type="hidden" name="REGION" value="<?php echo $REGION; ?>">
            <input type="hidden" name="CITY" value="<?php echo $CITY; ?>">
            <input type="hidden" name="HOTEL" value="<?php echo $HOTEL; ?>">
            <input type="hidden" name="FULL_NAME" value="<?php echo $FULL_NAME; ?>">
            <input type="hidden" name="EMAIL" value="<?php echo $EMAIL; ?>">
            <input type="hidden" name="CHECK_IN" value="<?php echo $CHECK_IN; ?>">
            <input type="hidden" name="CHECK_OUT" value="<?php echo $CHECK_OUT; ?>">
            <input type="hidden" name="GUESTS" value="<?php echo $GUESTS; ?>">
            <input type="hidden" name="PRICE_PER_NIGHT" value="<?php echo $PRICE_PER_NIGHT; ?>">
            <input type="hidden" id="clientSecret" value="<?php echo $paymentIntent->client_secret; ?>">

            <div id="card-element" class="form-control mb-3" style="padding: 10px; min-height: 50px;"></div>
            <div id="card-errors" class="text-danger mb-3" role="alert"></div>

            <button type="submit" class="btn btn-success">Confirm Payment</button>
            <a href="javascript:history.back()" class="btn btn-secondary">Cancel</a>
        </form>
    </div>





<!-- https://docs.stripe.com/js/initializing -->

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('pk_test_51ScI2MP2ITnf2U5McxHaPK397EH7MdEgKSO2euzFvsIRyQxjNk98RnhV2dAbxLbMyc76LP4UTSQsA19d95dCMTfE00EahtdxrW');
        const elements = stripe.elements();
        const card = elements.create('card');
        card.mount('#card-element');

        const form = document.getElementById('paymentForm');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const clientSecret = document.getElementById('clientSecret').value;

            // https://docs.stripe.com/payments/accept-a-payment
            const result = await stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: card,
                    billing_details: {
                        name: form.FULL_NAME.value,
                        email: form.EMAIL.value
                    }
                }
            });

            if (result.error) {
                document.getElementById('card-errors').textContent = result.error.message;
            } else if (result.paymentIntent.status === 'succeeded') {
                const hiddenForm = document.createElement('form');
                hiddenForm.method = 'POST';
                hiddenForm.action = 'confirm_booking.php';

                const inputs = form.querySelectorAll('input[type=hidden]');
                inputs.forEach(input => {
                    const el = document.createElement('input');
                    el.type = 'hidden';
                    el.name = input.name || input.id;
                    el.value = input.value;
                    hiddenForm.appendChild(el);
                });

                document.body.appendChild(hiddenForm);
                hiddenForm.submit();
            }
        });
    </script>
</body>

</html>