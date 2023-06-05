<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/connection.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/models/user_auth.php");

session_start();

$user = $_SESSION["user"];
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
$uuid = guidv4();
if (!isset($user) || !$user instanceof User) {
    return;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the form was submitted
    if (isset($_POST["receiver_uuid"]) && isset($_POST["message"])) {
        $receiver_uuid = $_POST["receiver_uuid"];
        $message = $_POST["message"];
        
        // Insert the message into the database
        $statement = $conn->prepare("INSERT INTO messages (uuid, sender_uuid, receiver_uuid, message) VALUES (?, ?, ?, ?)");
        $statement->execute([$uuid, $user->getUuid(), $receiver_uuid, $message]);
        
        // Redirect to the conversation page or display a success message
        header("Location: /chat.php?receiver_uuid=" . $receiver_uuid);
        exit();
    }
}

$receiver_uuid = $_GET["receiver_uuid"]; // Assuming you have a query parameter for the receiver UUID

// Retrieve conversation messages between the sender and receiver
$statement = $conn->prepare("SELECT * FROM messages WHERE (sender_uuid = ? AND receiver_uuid = ?) OR (sender_uuid = ? AND receiver_uuid = ?) ORDER BY created_at ASC");
$statement->execute([$user->getUuid(), $receiver_uuid, $receiver_uuid, $user->getUuid()]);
$messages = $statement->fetchAll();

// Display the conversation messages
foreach ($messages as $message) {
    // Display each message with its details
    echo $message["sender_uuid"] . ": " . $message["message"] . "<br>";
}
?>
