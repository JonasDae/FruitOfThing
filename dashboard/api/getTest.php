<?php
require 'connect.php';

$sql = "SELECT * 
		FROM test_table";

if($result = mysqli_query($con, $sql))
{
    $cr=0;
    while($row = mysqli_fetch_assoc($result))
    {
        $nieuwsartikels[$cr]['id'] = $row['id'];
        $nieuwsartikels[$cr]['naam'] = $row['naam'];
        $cr++;
    }

    echo json_encode($nieuwsartikels);
}
else
{
    http_response_code(404);
}
?>