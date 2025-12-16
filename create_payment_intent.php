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
$ROOM_TYPE = $_POST['ROOM_TYPE'];
$FULL_NAME = $_POST['FULL_NAME'];
$EMAIL = $_POST['EMAIL'];
$CHECK_IN = $_POST['CHECK_IN'];
$CHECK_OUT = $_POST['CHECK_OUT'];
$GUESTS = $_POST['GUESTS'];
$PRICE_PER_NIGHT = $_POST['PRICE_PER_NIGHT'];




// CHECK DUPLICATE DATES
$checkSql = " SELECT * FROM BOOKINGS WHERE EMAIL = '$EMAIL' AND CHECK_IN = '$CHECK_IN' AND CHECK_OUT = '$CHECK_OUT' ";

$checkResult = sqlsrv_query($conn, $checkSql);

if ($checkResult === false) {
    die(print_r(sqlsrv_errors(), true));
}

if (sqlsrv_fetch_array($checkResult)) {
    die('
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Duplicate Booking</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body { font-family: Arial, sans-serif; }
                .hero-section {
                    background: url("home.jpg") center/cover no-repeat;
                    height: 250px;
                    display: flex;
                    align-items: center;
                    color: white;
                    padding-left: 30px;
                }
                .hero-title {
                    background: rgba(0,0,0,0.5);
                    padding: 15px 20px;
                    border-radius: 6px;
                }
                .confirmation-box {
                    background-color: #ffffff;
                    padding: 25px;
                    border-radius: 8px;
                    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                    margin-top: -50px;
                }
                .btn-home { margin-top: 20px; }
                .navbar { background-color: #e63946 !important; }
                .nav-box {
                    background-color: #ffffff;
                    padding: 6px 12px;
                    border-radius: 8px;
                    margin-right: 8px;
                }
            </style>
        </head>

        <body>

        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand fw-bold nav-box" href="#">日本 nihon-GO</a>
            </div>
        </nav>

        <section class="hero-section mb-4">
            <h1 class="hero-title fw-bold">Booking Error</h1>
        </section>

        <main class="container mb-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="confirmation-box text-center">
                        <h2 class="fw-bold text-danger mb-3">Duplicate Booking Detected</h2>
                        <p>You already have a booking for these dates.</p>
                        <p>Please choose different dates or review your existing booking.</p>
                        <p>This booking was automatically cancelled.</p>
                        <a href="homepage.html" class="btn btn-danger btn-home">Return to Home</a>
                    </div>
                </div>
            </div>
        </main>

        <footer class="bg-dark text-light text-center p-3">
            <p class="mb-1">2025 Japan Travel Booking</p>
            <p class="mb-0">School project only</p>
        </footer>

        </body>
        </html>
    ');
}




// ROOM CAPACITY BLOCK
if ($ROOM_TYPE == "Suite") {
    $maxGuests = 7;
} elseif ($ROOM_TYPE == "Family") {
    $maxGuests = 6;
} elseif ($ROOM_TYPE == "Budget Friendly") {
    $maxGuests = 2;
} else {
    $maxGuests = 0;
}

if($GUESTS > $maxGuests){
    die('
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Duplicate Booking</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body { font-family: Arial, sans-serif; }
                .hero-section {
                    background: url("home.jpg") center/cover no-repeat;
                    height: 250px;
                    display: flex;
                    align-items: center;
                    color: white;
                    padding-left: 30px;
                }
                .hero-title {
                    background: rgba(0,0,0,0.5);
                    padding: 15px 20px;
                    border-radius: 6px;
                }
                .confirmation-box {
                    background-color: #ffffff;
                    padding: 25px;
                    border-radius: 8px;
                    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                    margin-top: -50px;
                }
                .btn-home { margin-top: 20px; }
                .navbar { background-color: #e63946 !important; }
                .nav-box {
                    background-color: #ffffff;
                    padding: 6px 12px;
                    border-radius: 8px;
                    margin-right: 8px;
                }
            </style>
        </head>

        <body>

        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand fw-bold nav-box" href="#">日本 nihon-GO</a>
            </div>
        </nav>

        <section class="hero-section mb-4">
            <h1 class="hero-title fw-bold">Booking Error</h1>
        </section>

        <main class="container mb-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="confirmation-box text-center">
                        <h2 class="fw-bold text-danger mb-3">Guest count exceeds room capacity.</h2>
                        <p>The selected room cannot accommodate '.$GUESTS.' guests.</p>
                        <p>Please select a different room.</p>
                        <p>This booking was automatically cancelled.</p>
                        <a href="homepage.html" class="btn btn-danger btn-home">Return to Home</a>
                    </div>
                </div>
            </div>
        </main>

        <footer class="bg-dark text-light text-center p-3">
            <p class="mb-1">2025 Japan Travel Booking</p>
            <p class="mb-0">School project only</p>
        </footer>

        </body>
        </html>
    ');
}



// Bawal paatras yung check-in and check-out

// https://www.php.net/manual/en/datetime.construct.php
$checkInDate  = new DateTime($CHECK_IN);
$checkOutDate = new DateTime($CHECK_OUT);

if ($checkOutDate <= $checkInDate) {
    die('
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Booking Error</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body { font-family: Arial, sans-serif; }
                .hero-section {
                    background: url("home.jpg") center/cover no-repeat;
                    height: 250px;
                    display: flex;
                    align-items: center;
                    color: white;
                    padding-left: 30px;
                }
                .hero-title {
                    background: rgba(0,0,0,0.5);
                    padding: 15px 20px;
                    border-radius: 6px;
                }
                .confirmation-box {
                    background-color: #ffffff;
                    padding: 25px;
                    border-radius: 8px;
                    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                    margin-top: -50px;
                }
                .btn-home { margin-top: 20px; }
                .navbar { background-color: #e63946 !important; }
                .nav-box {
                    background-color: #ffffff;
                    padding: 6px 12px;
                    border-radius: 8px;
                    margin-right: 8px;
                }
            </style>
        </head>
        <body>
            <nav class="navbar navbar-expand-lg">
                <div class="container">
                    <a class="navbar-brand fw-bold nav-box" href="#">日本 nihon-GO</a>
                </div>
            </nav>
            <section class="hero-section mb-4">
                <h1 class="hero-title fw-bold">Booking Error</h1>
            </section>
            <main class="container mb-5">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="confirmation-box text-center">
                            <h2 class="fw-bold text-danger mb-3">Invalid Dates</h2>
                            <p>Check-out date must be after check-in date.</p>
                            <a href="homepage.html" class="btn btn-danger btn-home">Return to Home</a>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="bg-dark text-light text-center p-3">
                <p class="mb-1">2025 Japan Travel Booking</p>
                <p class="mb-0">School project only</p>
            </footer>
        </body>
        </html>
    ');
}

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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Payment - <?php echo $HOTEL; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .hero-section {
            background: url("home.jpg") center/cover no-repeat;
            height: 250px;
            display: flex;
            align-items: center;
            color: white;
            padding-left: 30px;
        }

        .hero-title {
            background: rgba(0, 0, 0, 0.5);
            padding: 15px 20px;
            border-radius: 6px;
        }

        .payment-box {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: -50px;
        }

        .btn-home {
            margin-top: 20px;
        }

        .navbar {
            background-color: #e63946 !important;
        }

        .nav-box {
            background-color: #ffffff;
            padding: 6px 12px;
            border-radius: 8px;
            margin-right: 8px;
        }

        #card-element {
            padding: 10px;
            min-height: 50px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg" style="background-color: #ff3333;">
        <div class="container">
            <a class="navbar-brand fw-bold nav-box my-1" href="#">日本 nihon-GO</a>

        </div>
    </nav>

    <section class="hero-section mb-4">
        <h1 class="hero-title fw-bold">Complete Your<br>Booking Payment</h1>
    </section>

    <main class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="payment-box">
                    <h4 class="mb-3"><?php echo $HOTEL; ?> - <?php echo $CITY; ?></h4>
                    <p>Guest: <?php echo $FULL_NAME; ?></p>
                    <p>Total Amount: ₱<?php echo number_format($PRICE_PER_NIGHT * $days, 2); ?> for <?php echo $days; ?> night(s)</p>

                    <form id="paymentForm">
                        <input type="hidden" name="REGION" value="<?php echo $REGION; ?>">
                        <input type="hidden" name="CITY" value="<?php echo $CITY; ?>">
                        <input type="hidden" name="HOTEL" value="<?php echo $HOTEL; ?>">
                        <input type="hidden" name="ROOM_TYPE" value="<?php echo $ROOM_TYPE; ?>">
                        <input type="hidden" name="FULL_NAME" value="<?php echo $FULL_NAME; ?>">
                        <input type="hidden" name="EMAIL" value="<?php echo $EMAIL; ?>">
                        <input type="hidden" name="CHECK_IN" value="<?php echo $CHECK_IN; ?>">
                        <input type="hidden" name="CHECK_OUT" value="<?php echo $CHECK_OUT; ?>">
                        <input type="hidden" name="GUESTS" value="<?php echo $GUESTS; ?>">
                        <input type="hidden" name="PRICE_PER_NIGHT" value="<?php echo $PRICE_PER_NIGHT; ?>">
                        <input type="hidden" id="clientSecret" value="<?php echo $paymentIntent->client_secret; ?>">

                        <div id="card-element" class="mb-3"></div>
                        <div id="card-errors" class="text-danger mb-3" role="alert"></div>

                        <button type="submit" class="btn btn-success w-100">Confirm Payment</button>
                        <a href="javascript:history.back()" class="btn btn-secondary w-100 mt-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-light text-center p-3">
        <p class="mb-1">2025 Japan Travel Booking</p>
        <p class="mb-0">School project only</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>




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