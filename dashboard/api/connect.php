<?php

// db credentials
define('DB_HOST', 'server-pcfruit.database.windows.net');
define('DB_USER', 'FruitOfThings');
define('DB_PASS', 'ThingsOfFruit321654+');
define('DB_NAME', 'db_pcfruit');

// connect with the database
function connect(){
    $connect = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die($mysqli->error);

    // check connection
    if(mysqli_connect_errno($connect)){
        die("Failed to connect: " .mysqli_connect_error());
    }

    mysqli_set_charset($connect, "utf8");

    return $connect;
}

$con = connect();
?>