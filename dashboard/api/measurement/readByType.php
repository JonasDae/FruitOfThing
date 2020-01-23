<?php
require '../connect.php';

$id = $_GET['id'];

$sql ="	SELECT measurement.*, module.name AS module_name
		FROM measurement
		INNER JOIN module
		ON (measurement.module_id = module.id)
		WHERE measurement.fruit_type_id = '{$id}'
		ORDER BY date_time ASC";

if($result = sql_query($con, $sql))
{
    $data=[];
    $cr=0;
    while($row = sql_fetch_row($result))
    {
		$data[$cr]['id'] = $row['id'];
		$data[$cr]['fruit_type_id'] = $row['fruit_type_id'];
		$data[$cr]['module_id'] = $row['module_id'];
		$data[$cr]['module_name'] = $row['module_name'];
		$data[$cr]['date_time'] = $row['date_time'];
		$data[$cr]['dendrometer'] = $row['dendrometer'];
		$data[$cr]['watermark'] = $row['watermark'];
		$data[$cr]['temperature'] = $row['temperature'];
		$data[$cr]['humidity'] = $row['humidity'];

		$cr++;
		
		printf("%s\n",$data[$cr]['module_name']);
    }

    echo json_encode($data);
}
else
{
    http_response_code(404);
}
?>
