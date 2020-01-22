<?php
    require '../connect.php';

    $sql = "SELECT * 
            FROM notification";

    $data = [];

    if($result = sql_query($con, $sql))
    {
        $cr=0;
        while($row = sql_fetch_row($result))
        {
            $data[$cr]['id'] = $row['id'];
            $data[$cr]['title'] = $row['title'];
            $data[$cr]['description'] = $row['description'];
            $data[$cr]['date_time'] = $row['date_time'];
            $cr++;
        }

        echo json_encode($data);
    }
    else
    {
        http_response_code(404);
    }

?>
