<?php
require '../connect.php';

$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata)
{    
    date_default_timezone_set('Europe/Brussels');

    $request = json_decode($postdata);
    $connection_date = date("Y-m-d H:i:s");

    // Saniteze json
    $name = mysqli_real_escape_string($con, $request->name); //string
    $battery_level = mysqli_real_escape_string($con, $request->name); //int;
    $uptime = $connection_date;
    $last_connection = $connection_date;

    // Store Module
    $sql = "INSERT INTO modules (name, battery_level, uptime, last_connection)
            VALUES (
            '{$name}',
            '{$battery_level}',
            '{$uptime}',
            '{$last_connection}')";

    if(sql_query($con, $sql)) // Store succes
    {
        $newId = mysqli_insert_id($con);
        return $newId;
    }
    else // Store failed
    {
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
        http_response_code(422);
    }

    mysqli_close($con);
    return;
}
?>