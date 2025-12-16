<?php

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

$EMAIL = $_POST['EMAIL'];
$BOOKING_REF = $_POST['BOOKING_REF'];

$sql_1 = "SELECT * FROM BOOKINGS WHERE EMAIL = '$EMAIL' AND BOOKING_REF = '$BOOKING_REF'";
$result_1 = sqlsrv_query($conn, $sql_1);

if ($result_1 === false) {
    die(print_r(sqlsrv_errors(), true));
}

$row = sqlsrv_fetch_array($result_1, SQLSRV_FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Lookup</title>
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
            <a class="navbar-brand fw-bold nav-box my-1" href="#">日本 nihon-GO</a>
        </div>
    </nav>

    <section class="hero-section mb-4">
        <h1 class="hero-title fw-bold">Booking Lookup</h1>
    </section>

    <main class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="confirmation-box text-center">
                    <?php
                    if ($row) {
                        echo "<h2 class='fw-bold text-danger mb-3'>Booking Found!</h2>";
                        echo "<ul class='list-group list-group-flush mb-3 text-start'>";
                        echo "<li class='list-group-item'><strong>Booking Reference:</strong> " . $row['BOOKING_REF'] . "</li>";
                        echo "<li class='list-group-item'><strong>Full Name:</strong> " . $row['FULL_NAME'] . "</li>";
                        echo "<li class='list-group-item'><strong>Email:</strong> " . $row['EMAIL'] . "</li>";
                        echo "<li class='list-group-item'><strong>Region:</strong> " . $row['REGION'] . "</li>";
                        echo "<li class='list-group-item'><strong>City:</strong> " . $row['CITY'] . "</li>";
                        echo "<li class='list-group-item'><strong>Hotel:</strong> " . $row['HOTEL'] . "</li>";
                        echo "<li class='list-group-item'><strong>Check-in:</strong> " . $row['CHECK_IN']->format('Y-m-d') . "</li>";
                        echo "<li class='list-group-item'><strong>Check-out:</strong> " . $row['CHECK_OUT']->format('Y-m-d') . "</li>";
                        echo "<li class='list-group-item'><strong>Guest(s):</strong> " . $row['GUESTS'] . "</li>";
                        echo "<li class='list-group-item'><strong>Total Amount:</strong> Php " . number_format($row['TOTAL_AMOUNT'], 2) . "</li>";
                        echo "</ul>";
                    } else {
                        echo "<h2 class='fw-bold text-danger mb-3'>Booking Not Found</h2>";
                        echo "<p>No booking matches the email and reference number you entered.</p>";
                    }
                    ?>
                    <a href="search_bookings.html" class="btn btn-danger btn-home">Back to Lookup Form</a>
                    <a href="homepage.html" class="btn btn-danger btn-home">Back to Home</a>
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