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

$REGION = $_POST['REGION'];
$CITY = $_POST['CITY'];
$HOTEL = $_POST['HOTEL'];
$FULL_NAME = $_POST['FULL_NAME'];
$EMAIL = $_POST['EMAIL'];
$CHECK_IN = $_POST['CHECK_IN'];
$CHECK_OUT = $_POST['CHECK_OUT'];
$GUESTS = $_POST['GUESTS'];
$PRICE_PER_NIGHT = $_POST['PRICE_PER_NIGHT'];

$sql = "INSERT INTO BOOKINGS(REGION, CITY, HOTEL, FULL_NAME, EMAIL, CHECK_IN, CHECK_OUT, GUESTS, PRICE_PER_NIGHT, BOOKING_DATE)
        VALUES ('$REGION', '$CITY', '$HOTEL', '$FULL_NAME', '$EMAIL', '$CHECK_IN', '$CHECK_OUT', $GUESTS, $PRICE_PER_NIGHT, GETDATE())";

$result = sqlsrv_query($conn, $sql);

?>