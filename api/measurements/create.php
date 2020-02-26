<?php
// This api will be executed when the module wants to send the measurements to the database

require '../connect.php';

$postdata = file_get_contents("php://input");
// $postdata =  '{"module_id": 1, "battery_level": 100, "measure_date": "1582207450", "data": [{"sensor": 13, "value": 20.3},{"sensor": 14, "value": 30.5},{"sensor": 15, "value": 40.6},{"sensor": 16, "value": 50.1}]}';

if(isset($postdata) && !empty($postdata))
{    
    date_default_timezone_set('Europe/Brussels');
    $connection_date = date("Y-m-d H:i:s");

    // Saniteze json
    $request = json_decode($postdata, true);
    $module_id = intval(mysqli_real_escape_string($con, $request['module_id'])); //int
    $battery_level = intval(mysqli_real_escape_string($con, $request['battery_level'])); //int
    $measure_date = DateTime::createFromFormat('U', mysqli_real_escape_string($con, $request['measure_date']))->format('Y-m-d H:i:s'); 

    // Store measurements
	foreach($request['data'] as $data)
	{
        $sql = "INSERT INTO measurements (module_id, module_sensor_id, value, measure_date)
                VALUES (
                '{$module_id}',
                '{$data["sensor"]}',
                '{$data["value"]}',
                '{$measure_date}')";

        if(sql_query($con, $sql)) // Store succes
        {
            // Update module_sensor last_connection
            $sql = "UPDATE module_sensors SET last_connection = '$connection_date' WHERE id = '{$data["sensor"]}' LIMIT 1";
                
            if (sql_query($con, $sql)) {
                // echo "Module_sensor updated successfully";
            } else {
                echo "Error updating record: " . mysqli_error($con);
            }
        }else // Store failed
        {
            echo "Error: " . $sql . "<br>" . mysqli_error($con);
            http_response_code(422);
        }
	}

    // Update module last_connection
    $sql = "UPDATE modules SET last_connection = '$connection_date', battery_level = '$battery_level' WHERE id = '{$module_id}' LIMIT 1";
    
    if (sql_query($con, $sql)) {
        // echo "Module updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($con);
    }    

    mysqli_close($con);
}
?>
