<?php
    require 'connect.php';

    $sql = "SELECT * 
            FROM test_table";

    $testData = [];

    if($result = mysqli_query($con, $sql))
    {
        $cr=0;
        while($row = mysqli_fetch_assoc($result))
        {
            $testData[$cr]['id'] = $row['id'];
            $testData[$cr]['naam'] = $row['naam'];
            $cr++;
        }

        echo json_encode($testData);
    }
    else
    {
        http_response_code(404);
    }

?>