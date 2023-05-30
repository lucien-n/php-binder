<?php
require_once('./connection.php');

// * Check si la requête est de type "submit" donc si on a cliqué sur "Submit" dans notre formulaire
if (isset($_POST["submit"])) {
    // * Check si le contennu n'est pas vide pour ne pas créer un todo vide
    if (empty($_POST["content"])) {
        $errors = "Empty todo";
    } else {
        // * récupère le contennu du formulaire
        $content = $_POST["content"]; // * "content" est le tag name qu'on a set dans notre formulaire
        $sql = "INSERT INTO todos (content) VALUES ('" . $content . "')"; // * on crée notre requête avec le contennu de la todo
        $conn->query($sql); // * on exécute la query
        header("location: index.php"); // * on recharge la page
    }
}

// * Check si l'argument d'url "del_todo" existe
if (isset($_GET["del_todo"])) {
    $todo_id = $_GET["del_todo"]; // * on récupère l'id donné en argument d'url "/index.php?del_todo=<id>"
    $sql = "DELETE FROM todos WHERE id='" . $todo_id . "'"; // * on crée notre requête "DELETE" avec l'id de la todo
    $conn->query($sql); // * on éxécute la query
    header("location: index.php"); // * on recharge la page
}
?>

<html>

<body>
    <form action="/" method="POST">
        <!-- A noter que name="content" permet de récupérer le contennu de l'input dans le php -->
        <input type="text" name="content" id="content">
        <button type="submit" name="submit">Submit</button>
    </form>
    <ul>
        <?php
        // * Récupère tous les todos dans la base de données
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