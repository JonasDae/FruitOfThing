<?php
require '../connect.php';

$postdata = file_get_contents("php://input");

if (isset($postdata) && !empty($postdata)) {
    date_default_timezone_set('Europe/Brussels');

    // Data
    $type = mysqli_real_escape_string($con, 'App\Notifications\Arduino');
    $notifiable_type = mysqli_real_escape_string($con, 'App\User');
    $request = json_encode($postdata);
    $created_at = $updated_at = date("Y-m-d H:i:s");

    // Get Users
    $query = "SELECT id FROM users";
    $users = $con->query($query);

    // Store Notifications
    foreach ($users as $user) {
        $sql = "INSERT INTO notifications (type, notifiable_type, notifiable_id, data, created_at, updated_at)
            VALUES (
            '{$type}',
            '{$notifiable_type}',
            '{$user['id']}',
            '{$request}',
            '{$created_at}',
            '{$updated_at}')";

        if (!sql_query($con, $sql)) // Store succes
        {
            echo "Error: " . $sql . "<br>" . mysqli_error($con);
            http_response_code(422);
        }
    }

    mysqli_close($con);
    return;
}
?>
