<?php
require '../connect.php';

$postdata = file_get_contents("php://input");

// OLD
// $postdata = '{"module_id": 1, "battery_level": 34, "module_sensor_id": 3, "value": 20, "measure_date": "2020-01-30 10:20:20"}';
// NEW
//$postdata = '{"module_id": 1, "battery_level": 34, "measure_date": "2020-01-30 10:20:20", "data": [{"sensor": 1, "data": 20.3},{sensor: 2, data: 30.5},{sensor: 3, data: 40.6},{sensor: 4, data: 50.1}]}';

if(isset($postdata) && !empty($postdata))
{    
    date_default_timezone_set('Europe/Brussels');

    $request = json_decode($postdata);
    $connection_date = date("Y-m-d H:i:s");

    // Saniteze json
    $module_id = intval(mysqli_real_escape_string($con, $request->module_id)); //int
    $battery_level = intval(mysqli_real_escape_string($con, $request->battery_level)); //int
    $measure_date = DateTime::createFromFormat('Y-m-d H:i:s', mysqli_real_escape_string($con, $request->measure_date))->format('Y-m-d H:i:s');
    // date("Y-m-d H:i:s", strtotime(mysqli_real_escape_string($con, $request->measure_date))); //dateTime   

    // Store measurements
	foreach($request->data as $data)
	{
	/*
    $module_sensor_id = intval(mysqli_real_escape_string($con, $request->module_sensor_id)); //int
    $value = doubleval(mysqli_real_escape_string($con, $request->value)); //double
	*/
		echo "data element:" . $data;
		$sql = "INSERT INTO measurements (module_id, module_sensor_id, value, measure_date)
				VALUES (
				'{$module_id}',
				'{$data->sensor}',
				'{$data->value}',
				'{$measure_date}')";
	}


    if(sql_query($con, $sql)) // Store succes
    {
        // Update module last_connection
        $sql = "UPDATE modules SET last_connection = '$connection_date', battery_level = '$battery_level' WHERE id = '{$module_id}' LIMIT 1";
        
        if (sql_query($con, $sql)) {
            // echo "Module updated successfully";
        } else {
            echo "Error updating record: " . mysqli_error($con);
        }

        // Update module_sensor last_connection
        $sql = "UPDATE module_sensors SET last_connection = '$connection_date' WHERE id = '{$module_sensor_id}' LIMIT 1";
        
        if (sql_query($con, $sql)) {
            // echo "Module_sensor updated successfully";
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
