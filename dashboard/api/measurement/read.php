<?php
	header("Access-Control-Allow-Origin: *");
    require '../connect.php';

    $sql = "SELECT * 
            FROM measurement";

    $data = [];

    if($result = mysqli_query($con, $sql))
    {
        $cr=0;
        while($row = mysqli_fetch_assoc($result))
        {
            $data[$cr]['id'] = $row['id'];
            $data[$cr]['fruit_type_id'] = $row['fruit_type_id'];
            $data[$cr]['module_id'] = $row['module_id'];
            $data[$cr]['date_time'] = $row['date_time'];
            $data[$cr]['dendrometer'] = $row['dendrometer'];
            $data[$cr]['watermark'] = $row['watermark'];
            $data[$cr]['temperature'] = $row['temperature'];
            $data[$cr]['humidity'] = $row['humidity'];
            $cr++;
        }

        echo json_encode($data);
    }
    else
    {
        http_response_code(404);
    }

?>
