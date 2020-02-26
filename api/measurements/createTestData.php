<?php
require '../connect.php';

$module_id = intval($_GET['module_id']);
$battery_level = mt_rand(1,100);
// Start date
$date = '2019-09-01 00:00:00';
// End date
$end_date = '2020-02-25 00:00:00';

if(isset($module_id) && !empty($module_id))
{    
    // Set timezone
    date_default_timezone_set('Europe/Brussels');

    // $connection_date = date("Y-m-d H:i:s", strtotime("2019-12-24 09:00:00"));
    $connection_date = date("Y-m-d H:i:s");

    $moduleSensors = [];

    // Update module last_connection
    $sql = "UPDATE modules SET last_connection = '$connection_date', battery_level = '$battery_level' WHERE id = '{$module_id}' LIMIT 1";   
    if (sql_query($con, $sql)) {
        echo "Module updated successfully";
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
        // Update module_sensor last_connection
        foreach($moduleSensors as $moduleSensor)
        {  
            $sql = "UPDATE module_sensors SET last_connection = '$connection_date' WHERE id = '{$moduleSensor["id"]}' LIMIT 1";
            
            if (sql_query($con, $sql)) {
                echo "Module_sensor updated successfully";
            } else {
                echo "Error updating record: " . mysqli_error($con);
            }
        }

        // Loop between start date and end date
        echo "<br>DATE LOOP<br>";
        $value = 0.000;
        $valueGrootte = 0.000;

        while (strtotime($date) <= strtotime($end_date)) {
            // Store measurements per moduleSensor
            foreach($moduleSensors as $moduleSensor)
            {
                //Meting opslaan
                switch($moduleSensor['sensor_id']){
                    case 1: //Vruchtgrootte
                        $value = $valueGrootte + (mt_rand(0, 15)/100);
                        $valueGrootte = $value;
                    break;
                    case 2: // Temperatuur
                        $value = mt_rand(0,30);
                    break;
                    case 3: // Luchtvochtigheid
                        $value = mt_rand(35,70);
                    break;
                    case 4: // Bbodemvochtigheid
                        $value = mt_rand(0,200);
                    break;
                    case 5: // Bodemtemperatuur
                        $value = mt_rand(0,25);
                    break;
                    default :
                        $value = mt_rand(0,100);
                    break;
                }


                $sql = "INSERT INTO measurements (module_id, module_sensor_id, value, measure_date)
                    VALUES (
                    '{$module_id}',
                    '{$moduleSensor["id"]}',
                    '{$value}',
                    '{$date}')";

                if (sql_query($con, $sql)) {
                    // echo "New record created successfully. ";
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
                
            }
                    echo "New record created successfully. ";


            $date = date ("Y-m-d H:i:s", strtotime("+15 minutes", strtotime($date)));
        }
    }
    





    

    
        
        

    mysqli_close($con);
    return;
}
?>
