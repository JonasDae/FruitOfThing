<?php
require '../connect.php';

$id = $_GET['id'];

$sql ="	SELECT *
		FROM jos_zoomfiles
		INNER JOIN jos_zoom 
		ON (jos_zoomfiles.catid = jos_zoom.catid)
		WHERE jos_zoomfiles.catid = '{$id}'";

if($result = mysqli_query($con, $sql))
{
    $cr=0;
    while($row = mysqli_fetch_assoc($result))
    {
        $fotos[$cr]['id'] = $row['id'];

        $cr++;
    }

    echo json_encode($fotos);
}
else
{
    http_response_code(404);
}
?>