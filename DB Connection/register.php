<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
require_once 'db_functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {

    // receiving the post params
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // check if user is already existed with the same email
    if ($db->isUserExisted($email)) {
        // user already existed
        $response["error"] = FALSE;
        $response["uid"] = $user["per_id"];
        $response["user"]["name"] = $user["per_name"];
        $response["user"]["email"] = $user["per_email"];
        $response["user"]["created_at"] = $user["per_date_added"];
        $response["user"]["updated_at"] = $user["per_date_modified"];
        $response["error_msg"] = "Welcome Back " . $name;
        echo json_encode($response);
    } else {
        // create a new user
        $user = $db->storeUser($name, $email, $password);
        if ($user) {
            // user stored successfully
            $response["error"] = FALSE;
            $response["uid"] = $user["per_id"];
            $response["user"]["name"] = $user["per_name"];
            $response["user"]["email"] = $user["per_email"];
            $response["user"]["created_at"] = $user["per_date_added"];
            $response["user"]["updated_at"] = $user["per_date_modified"];
            echo json_encode($response);
        } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Unknown error occurred in registration!";
            echo json_encode($response);
        }
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (name, email or password) is missing!";
    echo json_encode($response);
}
?>
