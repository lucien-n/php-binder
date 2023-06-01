<?php

// generate UUid function
function guidv4($data = null)
{
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
function registerUser($username, $email, $password, $gender, $liked_gender, $age, $bio)
{
    $conn = require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/connection.php");
    $args = func_get_args();

    $args = array_map(function ($value) {
        return trim($value);
    }, $args);
    foreach ($args as $value) {
        if (empty($value)) {
            return "All fields are required";
        }
    }
    foreach ($args as $value) {
        if (preg_match("/([<|>])/", $value)) {
            return " <> characters are not allowed";
        }
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Email is not valid";
    }

    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        return "Email already exists, please try a different one";
    }

    if (strlen($username) > 50) {
        return "Username is too long";
    }

    $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        return "Username already exists, please try a different one";
    }
    // gender transform to tinyint for the database 
    $genderValue = 0;
    if ($gender == 'male') {
        $genderValue = 1;
    } elseif ($gender == 'female') {
        $genderValue = 2;
    } elseif ($gender == 'non-binary') {
        $genderValue = 3;
    }
    // liked_gender transform to tinyint for the database 
    $likedGenderValue = 0;
    if ($liked_gender == 'male') {
        $likedGenderValue = 1;
    } elseif ($liked_gender == 'female') {
        $likedGenderValue = 2;
    } elseif ($liked_gender == 'non-binary') {
        $likedGenderValue = 3;
    }

    //Hash password
    $hashed_password = password_hash('sha24', PASSWORD_DEFAULT);
    echo $hashed_password;
    $uuid = guidv4();
    // post to the database
    $stmt = $conn->prepare("INSERT INTO users (uuid, username, email, password_hash, gender, liked_gender, age, bio) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $uuid, $username, $email, $hashed_password, $genderValue, $likedGenderValue, $age, $bio);
    $stmt->execute();

    if ($stmt->affected_rows != 1) {
        return "An error occurred. Please try again";
    } else {
        return "success";
    }
}

// function connect() {
//     $mysqli = new mysqli(SERVER, USERNAME, PASSWORD, DATABASE);

//     if ($mysqli->connect_error) {
//         $error = $mysqli->connect_error;
//         $error_date = date("Y-m-d H:i:s");
//         $message = "{$error} | {$error_date} \r\n";
//         file_put_contents("db-log.txt", $message, FILE_APPEND);
//         return false;
//     } else {
//         return $mysqli;
//     }
// }

function connect()
{
    global $conn;

    if ($conn->connect_error) {
        $error = $conn->connect_error;
        $error_date = date("Y-m-d H:i:s");
        $message = "{$error} | {$error_date} \r\n";
        file_put_contents("db-log.txt", $message, FILE_APPEND);
        return false;
    } else {
        return $conn;
    }
}


?>