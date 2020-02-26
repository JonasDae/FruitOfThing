<?php
// This api will be executed everytime the module (Arduino) starts or restarts.
// The api will check if the module exists in the database
// If the module does not exist will it be created together with all the sensor connections in module_sensors
// If the module does exist the uptime will be updated
// If succes the api will return JSON with the module id and a array with all the module_sensors id's

header('Content-Type: application/json');
require '../connect.php';

$postdata = file_get_contents("php://input");
// $postdata = '{"module_identifier": "ARDUINO_test", "sensoren": [{"id": 1}, {"id": 2}, {"id": 3}, {"id": 4}, {"id": 5} ] }';

if(isset($postdata) && !empty($postdata))
{
    date_default_timezone_set('Europe/Brussels');
    $connection_date = date("Y-m-d H:i:s");

    // Saniteze json
    $request = json_decode($postdata);
    $module_identifier = mysqli_real_escape_string($con, $request->module_identifier); //string
    $battery_level = intval(mysqli_real_escape_string($con, $request->battery_level)); //int;
    $sensoren = $request->sensoren; //array

    // Check if module exist
    if($module_identifier != null){
        $sql = "SELECT * FROM modules WHERE identifier = '$module_identifier' LIMIT 1";
        $result = sql_query($con, $sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                // Update module uptime and connection
                $module_id = $row['id'];
                $sql = "UPDATE modules SET last_connection = '$connection_date', uptime = '$connection_date' WHERE id = '$module_id' LIMIT 1";
                $module_sensoren = [];
                
                if (sql_query($con, $sql)) {
                    // Creat for each sensor connected to the module a module_sensors
                    foreach($sensoren as $sensor){
                        $sql = "SELECT * FROM module_sensors WHERE module_id = '$module_id' AND sensor_id = '$sensor->id' LIMIT 1"; 
                        $result = sql_query($con, $sql);
                        if($result) // Module_sensor created with succes
                        {
                            if ($result->num_rows > 0) {
                                // output data of each row
                                while($row = $result->fetch_assoc()) {
                                    $module_sensoren[] = intval($row['id']);
                                }
                            }
                        }
                        else // Module_sensor failed creating
                        {
                            echo "Error: " . $sql . "<br>" . mysqli_error($con);
                            http_response_code(422);
                        }
                    }
                } else {
                    echo "Error updating record: " . mysqli_error($con);
                }
                
                // Returning JSON to the arduino with the module_id and module sensors
                $returnArray = json_encode(array('module_id' => intval($module_id), 'module_sensors' => $module_sensoren));
                echo $returnArray;
            }
        } else {
            //If module does not exist > create new module
            $sql = "INSERT INTO modules (name, identifier, uptime, last_connection)
            VALUES (
            '{$module_identifier}',
            '{$module_identifier}',
            '{$connection_date}',
            '{$connection_date}')";

            if(sql_query($con, $sql)) // Module created with succes
            {
                $module_id = mysqli_insert_id($con);
                $module_sensoren = [];

                // Creat for each sensor connected to the module a module_sensors
                foreach($sensoren as $sensor){
                    $sql = "INSERT INTO module_sensors (module_id, sensor_id, last_connection)
                    VALUES (
                    '{$module_id}',
                    '{$sensor->id}',
                    '{$connection_date}')"; 

                    if(sql_query($con, $sql)) // Module_sensor created with succes
                    {
                        $module_sensoren[] = intval(mysqli_insert_id($con));
                    }
                    else // Module_sensor failed creating
                    {
                        echo "Error: " . $sql . "<br>" . mysqli_error($con);
                        http_response_code(422);
                    }
                }
                
                // Returning JSON to the arduino with the module_id and module sensors
                $returnArray = json_encode(array('module_id' => intval($module_id), 'module_sensors' => $module_sensoren));
                echo $returnArray;
            }
            else // Module failed creating
            {
                echo "Error: " . $sql . "<br>" . mysqli_error($con);
                http_response_code(422);
            }
        }
    }else{
        echo "NO IDENTIFIER";
    }
    
    mysqli_close($con);
    return;
}
?>