<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/models/user_auth.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/models/user_binder.php');

//? Register **
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
function registerUser($username, $email, $password, $gender, $liked_gender, $age, $bio, $file_name)
{
    $conn = require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/connection.php");
    $args = func_get_args();

    $args = array_map(function ($value) {
        return is_array($value) ? $value : trim($value);
    }, $args);

    // Check if an image was uploaded
    if (!empty($_FILES['image']['tmp_name'])) {
        $file_name = trim($_FILES['image']['name']);
        echo "Uploaded file name: " . $file_name; // Debug output
        $image_path = "/uploads/" . basename($file_name);
    
        move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER["DOCUMENT_ROOT"] . $image_path);
    } else {
        $image_path = "/uploads/default.jpg"; // Default image if no image uploaded
        echo "No image uploaded"; // Debug output
    }
    

    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        header("location: /error.php?error=Email+already+exists");
        exit();
    }

    if (strlen($username) > 50) {
        return "Username is too long";
    }

    // gender transform to tinyint for the database 
    if ($gender == 'female') {
        $genderValue = 0;
    } elseif ($gender == 'male') {
        $genderValue = 1;
    } elseif ($gender == 'non-binary') {
        $genderValue = 2;
    } else {
        return "Invalid gender";
    }

    // liked_gender transform to tinyint for the database 
    if ($liked_gender == 'female') {
        $likedGenderValue = 0;
    } elseif ($liked_gender == 'male') {
        $likedGenderValue = 1;
    } elseif ($liked_gender == 'everyone') {
        $likedGenderValue = 3;
    } else {
        return "Invalid liked gender";
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $uuid = guidv4();

    // post to the database
    $stmt = $conn->prepare("INSERT INTO users (uuid, username, email, password_hash, gender, liked_gender, age, bio, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $uuid, $username, $email, $hashed_password, $genderValue, $likedGenderValue, $age, $bio, $image_path);
    $stmt->execute();

    // Update the binder object with the image URL
    $binder = new stdClass();
    $binder->getUsername = function () use ($username) {
        return $username;
    };
    $binder->getImage = function () use ($image_path) {
        return $image_path;
    };

    if ($stmt->affected_rows != 1) {
        header("location: /error.php?error=An+error+occurred");
        exit();
    } else {
        header("location: /index.php");
        exit();
    }
}


//? Login 
function login($email, $password)
{
    $conn = require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/connection.php");
    $email = trim($email);
    $password = trim($password);

    $email = htmlspecialchars(trim($email), ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars(trim($password), ENT_QUOTES, 'UTF-8');

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc(); // extract the records from the result

    if ($data == NULL) {
        header('location: /error.php?error=Wrong+email+or+password');
    }

    if (!isset($data['password_hash'])) {
        header('location: /error.php?error=Wrong+email+or+password');
    }

    if (password_verify($password, $data["password_hash"]) == FALSE) {
        header('location: /error.php?error=Wrong+email+or+password');
        return false;
    } else {
        $_SESSION["user"] = new User($data['id'], $data['uuid'], $data['username'], $data['password_hash'], $data['email'], $data['gender'], $data['liked_gender'], $data['image'], $data['age'], $data['bio'], $data['created_at'], $data['updated_at'], $data['last_seen']);
        return true;
    }
}
//? Logout 

function logout()
{
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    unset($_SESSION["user"]);
    header("Location: /auth/login.php");
    exit;
}


?>