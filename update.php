<?php
require_once('./connection.php');

$content = '';
// get the id from the irl the fill the input with the value from the database
if (isset($_GET['id'])) {
    $id = $_GET['id']; // the variable is global
    $sql = "SELECT content FROM todos WHERE id='$id'"; // retrieve the content from the database
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $content = $row["content"];
    } else {
        echo "No record found with the provided ID.";
    }
}
// * Update the todo list
if (isset($_GET["submit"])) {
    $sql = "UPDATE FROM todos WHERE id='$id'"; // update the todo list in the database
    $conn->query($sql);
    header("location: index.php"); // after submitting the todo list relocate to the main page
}
?>

<body>
    <form action="/" method="POST">
    <!-- /* $content is the value from the database */ -->
        <input type="text" name="content" id="content" value="<?php echo $content ?>">
        <button type="submit" name="submit">Update</button>
    </form>
</body>
</html>