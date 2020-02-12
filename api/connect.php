<?php
// CORS fix: FIXME remove
header("Access-Control-Allow-Origin: *");

// db credentials LOCAL
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'pcfruit');

// db credentials SINNERS
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'pcfruit');

// db credentials INFINITY
define('DB_HOST', 'sql107.epizy.com');
define('DB_USER', 'epiz_25192099');
define('DB_PASS', '9uWkPRY99pWL1');
define('DB_NAME', 'epiz_25192099_pcfruit_dashboard');

// connect with the database
function sql_connect() {
    // Create connection
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // check connection
    if(!$conn){
        die("Connection failed: " . mysqli_connect_error());
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
