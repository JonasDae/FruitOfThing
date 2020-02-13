<?php
require '../connect.php';

$module_id = intval($_GET['module_id']);
$battery_level = mt_rand(1,100);

if(isset($module_id) && !empty($module_id))
{    
    date_default_timezone_set('Europe/Brussels');

    // $connection_date = date("Y-m-d H:i:s", strtotime("2019-12-24 09:00:00"));
    $connection_date = date("Y-m-d H:i:s");

    $moduleSensors = [];

    // Update module last_connection
    $sql = "UPDATE modules SET last_connection = '$connection_date', battery_level = '$battery_level' WHERE id = '{$module_id}' LIMIT 1";   
    if (sql_query($con, $sql)) {
        // echo "Module updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($con);
    }

    // Get module sensors
    $sql = "SELECT * FROM module_sensors WHERE module_id = '{$module_id}'";
    if($result = sql_query($con, $sql))
    {
        $i = 0;
        while($row = mysqli_fetch_array($result))
        {
            $moduleSensors[$i]['id'] = intval($row['id']);
            $moduleSensors[$i]['module_id'] = intval($row['module_id']);
            $moduleSensors[$i]['sensor_id'] = intval($row['sensor_id']);
            
            $i++;
        }
    }
    else
    {
        echo "Error getting records: " . mysqli_error($con);
    }

    if(!empty($moduleSensors)){
        // Store measurements per moduleSensor
        foreach($moduleSensors as $moduleSensor)
        {
            // Update module_sensor last_connection
            $sql = "UPDATE module_sensors SET last_connection = '$connection_date' WHERE id = '{$moduleSensor["id"]}' LIMIT 1";
            
            if (sql_query($con, $sql)) {
                // echo "Module_sensor updated successfully";
            } else {
                echo "Error updating record: " . mysqli_error($con);
            }

            //Meting opslaan
            $value = mt_rand(0.00,10.99);
            $measure_date = $connection_date;
            for ($i = 1; $i <= 25; $i++) {
                
                $sql = "INSERT INTO measurements (module_id, module_sensor_id, value, measure_date)
		 		    VALUES (
		 		    '{$module_id}',
		 		    '{$moduleSensor["id"]}',
		 		    '{$value}',
                     '{$measure_date}')";

                $measure_date = date('Y-m-d H:i:s',strtotime('+15 minutes',strtotime($measure_date)));
                $value+= mt_rand(0.00,10.99);

                if (sql_query($con, $sql)) {
                    echo "New record created successfully. ";
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
            }

        }
    }

    mysqli_close($con);
    return;
}
?>
