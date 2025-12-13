<?php

// Load PHP Mailer API
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';





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

// uniqid(): https://www.php.net/manual/en/function.uniqid.php
// mt_rand(): https://www.php.net/manual/en/function.mt-rand.php
//  md5(): https://www.php.net/manual/en/function.md5.php
// substr(): https://www.php.net/manual/en/function.substr.php
// strtoupper(): https://www.php.net/manual/en/function.strtoupper.php
$BOOKING_REF = "BK-" . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));

$checkInDate  = new DateTime($CHECK_IN);
$checkOutDate = new DateTime($CHECK_OUT);
$NUMBER_OF_NIGHTS = $checkInDate->diff($checkOutDate)->days;

$TOTAL_AMOUNT = $NUMBER_OF_NIGHTS * $PRICE_PER_NIGHT;

$sql = "INSERT INTO BOOKINGS(REGION, CITY, HOTEL, FULL_NAME, EMAIL, CHECK_IN, CHECK_OUT, GUESTS, TOTAL_AMOUNT, BOOKING_REF, BOOKING_DATE)
        VALUES ('$REGION', '$CITY', '$HOTEL', '$FULL_NAME', '$EMAIL', '$CHECK_IN', '$CHECK_OUT', '$GUESTS', '$TOTAL_AMOUNT', '$BOOKING_REF', GETDATE())";

$result = sqlsrv_query($conn, $sql);

if ($result) {
    $mail = new PHPMailer(true);
    try {

        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'raileybasilio@gmail.com';
        $mail->Password   = 'fgah kscs mmcx joks';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('raileybasilio@gmail.com', 'Nihon-GO Booking');
        $mail->addAddress($EMAIL, $FULL_NAME);

        $mail->isHTML(true);
        $mail->Subject = "Booking Confirmation: $HOTEL";
        $mail->Body = "
            <h2>Booking Confirmed!</h2>
            <p>Dear $FULL_NAME,</p>
            <p>Your booking at <strong>$HOTEL</strong> has been confirmed.</p>
            <ul>
                <li><strong>Booking Reference:</strong> $BOOKING_REF</li>
                <li>Region: $REGION</li>
                <li>City: $CITY</li>
                <li>Guest(s): $GUESTS</li>
                <li>Check-in: $CHECK_IN</li>
                <li>Check-out: $CHECK_OUT</li>
                <li>Number of Nights: $NUMBER_OF_NIGHTS</li>
                <li><strong>Total Amount: Php $TOTAL_AMOUNT</strong></li>
            </ul>
            <p>Please keep your booking reference for verification.</p>
            <p>Thank you for booking with Nihon-GO!</p>
        ";

        $mail->send();
        // wag lagyan
    } catch (Exception $e) {
        // wag lagyan
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
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

        .confirmation-box {
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
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg" style="background-color: #ff3333;">
        <div class="container">
            <a class="navbar-brand fw-bold nav-box" href="homepage.html">日本 nihon-GO</a>
            <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <section class="hero-section mb-4">
        <h1 class="hero-title fw-bold">Booking Confirmation</h1>
    </section>

    <main class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="confirmation-box text-center">
                    <?php if ($result): ?>
                        <h2 class="fw-bold text-danger mb-3">Booking Confirmed!</h2>
                        <p>Thank you, <strong><?php echo $FULL_NAME; ?></strong>.</p>
                        <p>Your booking at <strong><?php echo $HOTEL; ?></strong> has been successfully recorded.</p>
                        <ul class="list-group list-group-flush mb-3 text-start">
                            <li class="list-group-item"><strong>Region:</strong> <?php echo $REGION; ?></li>
                            <li class="list-group-item"><strong>City:</strong> <?php echo $CITY; ?></li>
                            <li class="list-group-item"><strong>Guest(s):</strong> <?php echo $GUESTS; ?></li>
                            <li class="list-group-item"><strong>Check-in:</strong> <?php echo $CHECK_IN; ?></li>
                            <li class="list-group-item"><strong>Check-out:</strong> <?php echo $CHECK_OUT; ?></li>
                            <li class="list-group-item"><strong>Total Amount:</strong> Php <?php echo $TOTAL_AMOUNT; ?></li>
                        </ul>
                        <p>A confirmation email has been sent to <strong><?php echo ($EMAIL); ?></strong>.</p>
                        <a href="homepage.html" class="btn btn-danger btn-home">Return to Home</a>
                    <?php else: ?>
                        <h2 class="fw-bold text-danger mb-3">Booking Failed</h2>
                        <p>Sorry, something went wrong with your booking.</p>
                        <a href="shinjukuskylinehotel.html" class="btn btn-danger btn-home">Try Again</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-light text-center p-3">
        <p class="mb-1">2025 Japan Travel Booking</p>
        <p class="mb-0">School project only</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>