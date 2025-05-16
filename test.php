<?php
$host = 'smtp.gmail.com';
$port = 587;
$connection = @fsockopen($host, $port, $errno, $errstr, 10);

if ($connection) {
    echo "Connected to $host on port $port";
    fclose($connection);
} else {
    echo "Failed to connect to $host on port $port. Error: $errstr ($errno)";
}
?>
