<?php
require '../connect.php';

$postdata = file_get_contents("php://input");
// $postdata = '{"module_id":1,"module_sensor_id":3,"value":69.69,"measure_date":2020-01-29 14:05:03}';

if(isset($postdata) && !empty($postdata))
{    
    date_default_timezone_set('Europe/Brussels');

    $request = json_decode($postdata);
    $connection_date = date("Y-m-d H:i:s");

    // Saniteze json
    $module_id = intval(mysqli_real_escape_string($con, $request->module_id)); //int
    $module_sensor_id = intval(mysqli_real_escape_string($con, $request->module_sensor_id)); //int
    $value = doubleval(mysqli_real_escape_string($con, $request->value)); //double
    $measure_date = DateTime::createFromFormat('Y-m-d H:i:s', mysqli_real_escape_string($con, $request->measure_date))->format('Y-m-d H:i:s');
    // date("Y-m-d H:i:s", strtotime(mysqli_real_escape_string($con, $request->measure_date))); //dateTime   

    //TEST
    // $module_id = 1; //int
    // $module_sensor_id = 3; //int
    // $value = rand(0.00,50.99); //double
	// $measure_date = date("Y-m-d H:i:s"); //dateTime   

    // Store measurement
    $sql = "INSERT INTO measurements (module_id, module_sensor_id, value, measure_date)
            VALUES (
            '{$module_id}',
            '{$module_sensor_id}',
            '{$value}',
            '{$measure_date}')";

    if(sql_query($con, $sql)) // Store succes
    {
        // Update module last_connection
        $sql = "UPDATE modules SET last_connection = '$connection_date' WHERE id = '{$module_id}' LIMIT 1";
        
        if (sql_query($con, $sql)) {
            // echo "Record updated successfully";
        } else {
            echo "Error updating record: " . mysqli_error($con);
        }

        // Update module_sensor last_connection
        $sql = "UPDATE module_sensors SET last_connection = '$connection_date' WHERE id = '{$module_sensor_id}' LIMIT 1";
        
        if (sql_query($con, $sql)) {
            // echo "Record updated successfully";
        } else {
            echo "Error updating record: " . mysqli_error($con);
        }
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