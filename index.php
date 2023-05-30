<?php
require_once('./connection.php');

if (isset($_POST["submit"])) {
    if (empty($_POST["content"])) {
        $errors = "Empty todo";
    } else {
        $content = $_POST["content"];
        $sql = "INSERT INTO todos (content) VALUES ('" . $content . "')";
        $conn->query($sql);
        header("location: index.php");
    }
}

if (isset($_GET["del_todo"])) {
    $todo_id = $_GET["del_todo"];
    $conn->query("DELETE FROM todos WHERE id='" . $todo_id . "'");
    header("location: index.php");
}
?>

<html>

<body>
    <form action="/" method="POST">
        <input type="text" name="content" id="content">
        <button type="submit" name="submit">Submit</button>
    </form>
    <ul>
        <?php
        $myValues = $conn->query("SELECT * FROM todos");

        if ($myValues->num_rows > 0) {
            while ($row = $myValues->fetch_assoc()) {
                ?>
                <li>
                    <?php echo $row["content"] ?>
                    <a href="index.php?del_todo=<?php echo $row["id"] ?>">delete</a>
                    <?php
                    ?>
                </li>
            <?php }
        } ?>
    </ul>
</body>

</html>