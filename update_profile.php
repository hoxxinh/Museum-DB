<?php
session_start();

include("classes/connect.php");
include("classes/user.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // retrieve form data
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // assuming this is the current password, 
                                    // might need additional fields for new passwords, etc.

    // validate form data

    // update user information in the database
    $user = new User();
    $update_result = $user->update_data($_SESSION['museum_userid'], $firstName, $lastName, $email, $password);

    if ($update_result) {
        // send JSON response for success
        echo json_encode(['success' => true]);
        exit;
    } else {
        // send JSON response for failure
        echo json_encode(['success' => false, 'message' => 'Failed to update profile.']);
        exit;
    }
} else {
    // send JSON response for invalid request method
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}
?>
