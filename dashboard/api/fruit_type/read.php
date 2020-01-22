<?php
	header("Access-Control-Allow-Origin: *");
    require '../connect.php';

    $sql = "SELECT * 
            FROM fruit_type
            ORDER BY name";

    $data = [];

    if($result = sql_query($con, $sql))
    {
        $cr=0;
        while($row = sql_fetch_row($result))
        {
            $data[$cr]['id'] = $row['id'];
            $data[$cr]['name'] = $row['name'];
            $cr++;
        }

        echo json_encode($data);
    }
    else
    {
        http_response_code(404);
    }

?>
