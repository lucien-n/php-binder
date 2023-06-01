<?php

// generate UUid function
function guidv4($data = null) {
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

    $genderValue = 0;
    if ($gender == 'male') {
        $genderValue = 1;
    } elseif ($gender == 'female') {
        $genderValue = 2;
    } elseif ($gender == 'non-binary') {
        $genderValue = 3;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $uuid = guidv4();
    $stmt = $conn->prepare("INSERT INTO users (uuid, username, email, password_hash, gender, liked_gender, age, bio) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssisiss", $uuid, $username, $email, $hashed_password, $genderValue, $liked_gender, $age, $bio);
    $stmt->execute();

    if ($stmt->affected_rows != 1) {
        return "An error occurred. Please try again";
    } else {
        return "success";
    }
}

?>