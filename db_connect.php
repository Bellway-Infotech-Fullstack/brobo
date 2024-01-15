<?php

function connectToDatabase() {
    // Assuming you have a database connection
     $host = "localhost";
     $user = "u929863268_brobo";
     $password = "47/vjTXh5/";
     $database = "u929863268_brobo";

    $conn = mysqli_connect($host, $user, $password, $database);

    if (!$conn) {
        echo("Connection failed: " . mysqli_connect_error());
        // Handle the connection error appropriately (e.g., exit script, log, etc.)
    }

    return $conn;
}
?>
