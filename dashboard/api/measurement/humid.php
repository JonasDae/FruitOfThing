<?php
    require '../connect.php';

    $sql = "SELECT * 
            FROM measurement";

    $data = [];

    if($result = mysqli_query($con, $sql))
    {
        $cr=0;
        while($row = mysqli_fetch_assoc($result))
        {
            $data[$cr] = $row['humidity'];
            $cr++;
        }

        echo json_encode($data);
    }
    else
    {
        http_response_code(404);
    }

?>
