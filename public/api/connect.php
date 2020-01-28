<?php
// CORS fix: FIXME remove
header("Access-Control-Allow-Origin: *");
// db credentials
define('DB_HOST', 'db.sinners.be');
define('DB_USER', 'floriandh');
define('DB_PASS', 'yu0p7fOrDc3g');
define('DB_NAME', 'floriandh_pcfruit');

// connect with the database
function sql_connect() {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // check connection
    if($conn->connect_error){
        die("Failed to connect: ");
        // die("Failed to connect: " $conn->connect_error);
    }

    mysqli_set_charset($conn, "utf8");
    return $conn;    
}
function sql_query($connection, $query) {
	return mysqli_query($connection, $query);
}
function sql_fetch_row($result) {
	return mysqli_fetch_assoc($result);
}
$con = sql_connect();
?>
